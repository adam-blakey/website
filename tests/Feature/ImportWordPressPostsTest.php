<?php

use App\Console\Commands\ImportWordPressPosts;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Image as GdImage;

it('detects images from their bytes', function () {
    expect(ImportWordPressPosts::detectImageMimeType(GdImage::fromBlank(2, 2)->toString(GdImage::PNG)))->toBe('image/png')
        ->and(ImportWordPressPosts::detectImageMimeType(GdImage::fromBlank(2, 2)->toString(GdImage::JPEG)))->toBe('image/jpeg');
});

it('rejects files that are not raster images', function (string $contents) {
    expect(ImportWordPressPosts::detectImageMimeType($contents))->toBeNull();
})->with([
    'php' => '<?php system($_GET["c"]);',
    'svg' => '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
    'empty' => '',
]);

it('only overwrites posts that have no local edits', function (bool $keepLocalEdits, string $expectedTitle) {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    DB::purge('sqlite');
    Artisan::call('migrate');
    BlogPost::create(['wordpress_id' => 7, 'title' => 'Edited here', 'slug' => 'post', 'content' => '<p>Mine</p>', 'published_at' => now(), 'keep_local_edits' => $keepLocalEdits]);
    $wordpressPost = ['id' => 7, 'title' => ['rendered' => 'From WordPress'], 'slug' => 'post', 'content' => ['rendered' => '<p>Theirs</p>'], 'date_gmt' => '2026-01-01T00:00:00'];

    $command = app(ImportWordPressPosts::class);
    $command->setOutput(new Illuminate\Console\OutputStyle(new Symfony\Component\Console\Input\ArrayInput([]), new Symfony\Component\Console\Output\NullOutput));
    (fn () => $this->importPost($wordpressPost, true))->call($command);

    expect(BlogPost::sole()->title)->toBe($expectedTitle);
})->with([
    'kept' => [true, 'Edited here'],
    'not kept' => [false, 'From WordPress'],
]);
