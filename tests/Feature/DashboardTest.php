<?php

use App\Helpers\ScheduleRuns;
use App\Models\BlogPost;
use App\Models\Concert;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    DB::purge('sqlite');
    Artisan::call('migrate');
    config(['services.goatcounter.code' => 'example']);
    Http::preventStrayRequests();
});

function admin(): User
{
    return User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'correct-horse-battery']);
}

function importedConcert(array $attributes = []): Concert
{
    return Concert::create([
        'name' => 'Autumn',
        'slug' => 'blackwood-1-autumn',
        'start_datetime' => now()->addWeek(),
        'location' => 'Hall',
        'ensemble' => 'Blackwood Clarinets',
        'external_id' => 'blackwood_1',
        ...$attributes,
    ]);
}

it('asks for a login on every dashboard page', function (string $path) {
    $this->get($path)->assertUnauthorized()->assertHeader('WWW-Authenticate', 'Basic');
})->with(['/dashboard', '/dashboard/schedule', '/dashboard/posts', '/dashboard/posts/create', '/dashboard/concerts', '/dashboard/concerts/create']);

it('logs in with a dashboard user created on the command line', function () {
    $this->artisan('app:create-admin', ['email' => 'admin@example.test'])
        ->expectsQuestion('Password (at least 12 characters)', 'correct-horse-battery')
        ->assertSuccessful();
    Http::fake(['example.goatcounter.com/*' => Http::response(status: 404)]);

    $this->withHeader('Authorization', 'Basic ' . base64_encode('admin@example.test:correct-horse-battery'))
        ->get('/dashboard')
        ->assertOk();
});

it('refuses a short dashboard password', function () {
    $this->artisan('app:create-admin', ['email' => 'admin@example.test'])
        ->expectsQuestion('Password (at least 12 characters)', 'short')
        ->assertFailed();

    expect(User::count())->toBe(0);
});

it('shows visits from the public GoatCounter counter', function () {
    $post = BlogPost::create(['title' => 'Popular', 'slug' => 'popular', 'content' => '<p>Hi</p>', 'published_at' => now()]);
    Http::fake([
        'example.goatcounter.com/counter/TOTAL.json?*' => Http::response(['count' => '3']),
        'example.goatcounter.com/counter/TOTAL.json' => Http::response(['count' => '1,234']),
        'example.goatcounter.com/counter/blog/popular.json' => Http::response(['count' => '56']),
    ]);

    $this->actingAs(admin())->get('/dashboard')
        ->assertOk()
        ->assertSee('1,234')
        ->assertSee('data-bs-values="' . implode(',', array_fill(0, 14, 3)) . '"', false)
        ->assertSeeInOrder(['Popular', '56']);

    Http::assertSent(fn ($request) => $request->url() === 'https://example.goatcounter.com/counter/TOTAL.json?start=' . now()->toDateString() . '&end=' . now()->toDateString());
});

it('still loads when GoatCounter is down, and tries again next time', function () {
    Http::fake(['example.goatcounter.com/*' => Http::response(status: 500)]);

    $this->actingAs(admin())->get('/dashboard')->assertOk()->assertSee('GoatCounter is unavailable');

    Http::swap(new Illuminate\Http\Client\Factory);
    Http::fake(['example.goatcounter.com/*' => Http::response(['count' => '9'])]);
    $this->get('/dashboard')->assertOk()->assertDontSee('GoatCounter is unavailable');
});

it('lists every scheduled task with its latest failure', function () {
    ScheduleRuns::record('music:fetch', false, 'Last.fm is down');

    $this->actingAs(admin())->get('/dashboard/schedule')
        ->assertOk()
        ->assertSee(['app:send-contact-confirmation-emails', 'cloudflare:reload', 'Never run', 'Failed', 'Last.fm is down']);
});

it('creates a post written on the site', function () {
    $this->actingAs(admin())->post('/dashboard/posts', [
        'title' => 'Written here',
        'slug' => 'written-here',
        'published_at' => '2026-11-21T09:00',
        'content' => '<p>Hello</p>',
    ])->assertRedirect('/dashboard/posts');

    expect(BlogPost::sole())
        ->wordpress_id->toBeNull()
        ->keep_local_edits->toBeFalse()
        ->published_at->format('Y-m-d H:i')->toBe('2026-11-21 09:00');
    $this->get('/blog/written-here')->assertOk()->assertSee('Hello');
});

it('rejects a post slug that is already taken', function () {
    BlogPost::create(['title' => 'First', 'slug' => 'taken', 'content' => '<p>Hi</p>', 'published_at' => now()]);

    $this->actingAs(admin())
        ->from('/dashboard/posts/create')
        ->post('/dashboard/posts', ['title' => 'Second', 'slug' => 'taken', 'published_at' => '2026-11-21T09:00', 'content' => '<p>Hi</p>'])
        ->assertRedirect('/dashboard/posts/create')
        ->assertSessionHasErrors('slug');

    expect(BlogPost::count())->toBe(1);
});

it('keeps or releases local edits to an imported post', function (string $keepLocalEdits, bool $expected) {
    $post = BlogPost::create(['wordpress_id' => 7, 'title' => 'Imported', 'slug' => 'imported', 'content' => '<p>Hi</p>', 'published_at' => now()]);

    $this->actingAs(admin())->put("/dashboard/posts/{$post->id}", [
        'title' => 'Fixed typo',
        'slug' => 'imported',
        'published_at' => '2026-11-21T09:00',
        'content' => '<p>Hi</p>',
        'keep_local_edits' => $keepLocalEdits,
    ])->assertRedirect('/dashboard/posts');

    expect($post->fresh())->title->toBe('Fixed typo')->keep_local_edits->toBe($expected);
})->with([
    'switch on' => ['1', true],
    'switch off' => ['0', false],
]);

it('switches local edits on by default when editing an imported item', function () {
    $concert = importedConcert();

    $this->actingAs(admin())->get("/dashboard/concerts/{$concert->id}/edit")
        ->assertOk()
        ->assertSee('name="keep_local_edits" value="1" checked', false);
});

it('hides the local edits switch for items made on the site', function () {
    $concert = importedConcert(['external_id' => null, 'slug' => 'manual']);

    $this->actingAs(admin())->get("/dashboard/concerts/{$concert->id}/edit")
        ->assertOk()
        ->assertDontSee('keep_local_edits');
});

it('creates a concert with a known ensemble only', function () {
    $concert = [
        'name' => 'Recital',
        'slug' => 'recital',
        'ensemble' => 'Blackwood Clarinets',
        'start_datetime' => '2026-12-05T19:30',
        'location' => 'Home',
    ];

    $this->actingAs(admin())->post('/dashboard/concerts', [...$concert, 'ensemble' => 'Someone else'])->assertSessionHasErrors('ensemble');
    $this->post('/dashboard/concerts', [...$concert, 'end_datetime' => '2026-12-05T18:00'])->assertSessionHasErrors('end_datetime');
    $this->post('/dashboard/concerts', $concert)->assertRedirect('/dashboard/concerts');

    expect(Concert::sole())->external_id->toBeNull()->start_datetime->format('Y-m-d H:i')->toBe('2026-12-05 19:30');
});

it('deletes a concert', function () {
    $concert = importedConcert();

    $this->actingAs(admin())->delete("/dashboard/concerts/{$concert->id}")->assertRedirect('/dashboard/concerts');

    expect(Concert::count())->toBe(0);
});

it('shows the posts and concerts lists', function () {
    BlogPost::create(['title' => 'A post', 'slug' => 'a-post', 'content' => '<p>Hi</p>', 'published_at' => now()]);
    importedConcert(['keep_local_edits' => true]);

    $this->actingAs(admin());
    $this->get('/dashboard/posts')->assertOk()->assertSee(['A post', 'Manual']);
    $this->get('/dashboard/concerts')->assertOk()->assertSee(['Autumn', 'Local edits kept']);
});
