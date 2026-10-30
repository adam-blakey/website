<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Nette\Utils\Image as GdImage;

class Image
{
    public const WIDTHS = [128, 256, 512, 1024, 2048];

    public function __construct(public readonly string $path)
    {
    }

    public static function all(string $directory = 'resources/img'): array
    {
        return array_map(
            fn ($file) => new self($directory . '/' . $file->getFilename()),
            File::files(base_path($directory)),
        );
    }

    public function width(): int
    {
        return getimagesize(base_path($this->path))[0];
    }

    public function variantPath(int $width): string
    {
        return dirname($this->path) . '/sizes/' . pathinfo($this->path, PATHINFO_FILENAME) . "-{$width}.webp";
    }

    public function widths(): array
    {
        return array_values(array_filter(self::WIDTHS, fn ($width) => file_exists(base_path($this->variantPath($width)))));
    }

    public function url(?int $width = null): string
    {
        $variant = $width ? collect($this->widths())->first(fn ($w) => $w >= $width) : null;

        return Vite::asset($variant ? $this->variantPath($variant) : $this->path);
    }

    public function srcset(): string
    {
        return collect($this->widths())
            ->map(fn ($width) => Vite::asset($this->variantPath($width)) . " {$width}w")
            ->push(Vite::asset($this->path) . " {$this->width()}w")
            ->implode(', ');
    }

    public function generate(): array
    {
        $source = GdImage::fromFile(base_path($this->path));
        $widths = array_values(array_filter(self::WIDTHS, fn ($width) => $width < $source->getWidth()));

        File::ensureDirectoryExists(base_path(dirname($this->variantPath(0))));
        foreach ($widths as $width) {
            (clone $source)->resize($width, null)->save(base_path($this->variantPath($width)), 80);
        }

        return $widths;
    }
}
