<?php

use App\Helpers\Image;
use Illuminate\Support\Facades\File;
use Nette\Utils\Image as GdImage;

it('generates webp copies narrower than the original', function () {
    $directory = 'storage/framework/testing/img';
    File::ensureDirectoryExists(base_path($directory));
    GdImage::fromBlank(600, 300)->save(base_path("$directory/test.png"));

    $image = new Image("$directory/test.png");

    expect($image->generate())->toBe([128, 256, 512])
        ->and($image->widths())->toBe([128, 256, 512])
        ->and(getimagesize(base_path($image->variantPath(256))))->toMatchArray([256, 128]);

    File::deleteDirectory(base_path($directory));
});
