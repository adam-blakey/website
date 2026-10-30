<?php

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    Artisan::call('migrate');
    Carbon::setTestNow('2026-10-30 12:00:00');
});

function fakeConcertSources(string $blackwoodStart, string $cirrusStart): void
{
    $mecEvent = fn (int $id, string $slug) => [
        'id' => $id,
        'slug' => $slug,
        'date' => '2026-01-01T09:00:00',
        'link' => "https://blackwoodclarinets.co.uk/events/{$slug}/",
        'title' => ['rendered' => 'Autumn &amp; Winter'],
        'content' => ['rendered' => '<p>📍 <strong>Old Hall</strong></p>'],
    ];
    $eventPage = fn (string $start) => '<script type="application/ld+json">{"@context":"https://schema.org","@graph":[{"@type":"WebPage"}]}</script>'
        .'<script type="application/ld+json">{"@context":"http://schema.org","@type":"Event","startDate":"'.$start.'","endDate":"'.substr($start, 0, 10).'T21:00:00+00:00","location":{"@type":"Place","name":"St Mary\'s Church"}}</script>';

    Http::fake([
        'blackwoodclarinets.co.uk/wp-json/*' => Http::response([$mecEvent(1, 'autumn'), $mecEvent(2, 'spring')]),
        'blackwoodclarinets.co.uk/events/autumn/' => Http::response($eventPage($blackwoodStart)),
        'blackwoodclarinets.co.uk/events/spring/' => Http::response($eventPage('2026-04-01T19:30:00+01:00')),
        'cirrusquartet.uk/*' => Http::response(['events' => [[
            'id' => 7,
            'slug' => 'cirrus-live',
            'title' => 'Cirrus Live',
            'description' => '<p>Quartets</p>',
            'start_date' => $cirrusStart,
            'end_date' => '2026-12-01 21:00:00',
            'timezone' => 'Europe/London',
            'image' => false,
            'venue' => ['venue' => 'The Studio', 'city' => 'Leeds'],
            'website' => '',
        ]]]),
    ]);
}

it('imports concerts at their scheduled time and skips past ones', function () {
    fakeConcertSources('2026-11-14T19:30:00+00:00', '2026-12-01 19:00:00');

    Artisan::call('concerts:import');

    expect(Concert::pluck('external_id')->sort()->values()->all())->toBe(['blackwood_1', 'cirrus_7']);

    $blackwood = Concert::firstWhere('external_id', 'blackwood_1');
    expect($blackwood->start_datetime->format('Y-m-d H:i'))->toBe('2026-11-14 19:30')
        ->and($blackwood->end_datetime->format('H:i'))->toBe('21:00')
        ->and($blackwood->location)->toBe("St Mary's Church")
        ->and($blackwood->name)->toBe('Autumn & Winter');

    $cirrus = Concert::firstWhere('external_id', 'cirrus_7');
    expect($cirrus->start_datetime->format('Y-m-d H:i'))->toBe('2026-12-01 19:00')
        ->and($cirrus->location)->toBe('The Studio, Leeds')
        ->and($cirrus->ticket_url)->toBeNull();
});

it('updates concerts that have been rescheduled', function () {
    fakeConcertSources('2026-11-14T19:30:00+00:00', '2026-12-01 19:00:00');
    Artisan::call('concerts:import');

    Http::swap(new Illuminate\Http\Client\Factory);
    fakeConcertSources('2026-11-21T18:00:00+00:00', '2026-12-01 20:00:00');
    Artisan::call('concerts:import');

    expect(Concert::count())->toBe(2)
        ->and(Concert::firstWhere('external_id', 'blackwood_1')->start_datetime->format('Y-m-d H:i'))->toBe('2026-11-21 18:00')
        ->and(Concert::firstWhere('external_id', 'cirrus_7')->start_datetime->format('H:i'))->toBe('20:00');
});

it('removes upcoming concerts that a source no longer lists, keeping manual ones', function () {
    fakeConcertSources('2026-11-14T19:30:00+00:00', '2026-12-01 19:00:00');
    Artisan::call('concerts:import');
    Concert::create(['name' => 'Solo recital', 'slug' => 'solo', 'start_datetime' => '2026-12-05 19:00:00', 'location' => 'Home', 'ensemble' => 'Blackwood Clarinets']);

    Http::swap(new Illuminate\Http\Client\Factory);
    Http::fake([
        'blackwoodclarinets.co.uk/wp-json/*' => Http::response([[
            'id' => 2,
            'slug' => 'spring',
            'link' => 'https://blackwoodclarinets.co.uk/events/spring/',
            'title' => ['rendered' => 'Spring'],
            'content' => ['rendered' => ''],
        ]]),
        'blackwoodclarinets.co.uk/events/spring/' => Http::response('<script type="application/ld+json">{"@type":"Event","startDate":"2026-04-01T19:30:00+01:00"}</script>'),
        'cirrusquartet.uk/*' => Http::response(['events' => []]),
    ]);
    Artisan::call('concerts:import');

    expect(Concert::pluck('slug')->sort()->values()->all())->toBe(['cirrus-live', 'solo']);
});

it('clears all concerts', function () {
    fakeConcertSources('2026-11-14T19:30:00+00:00', '2026-12-01 19:00:00');
    Artisan::call('concerts:import');

    expect(Artisan::call('concerts:clear', ['--force' => true]))->toBe(0)
        ->and(Concert::count())->toBe(0);
});
