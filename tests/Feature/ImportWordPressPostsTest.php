<?php

use App\Console\Commands\ImportWordPressPosts;
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
