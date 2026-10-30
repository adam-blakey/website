<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SimpleIconsProvider extends ServiceProvider
{
    private $iconsPath;
    private $defaultAttributes = [
        'fill' => 'currentColor',
        'stroke' => 'none',
        // Every icon sits beside text or inside a labelled link, so it's decorative
        'aria-hidden' => 'true',
    ];

    public function register(): void
    {
        $this->iconsPath = base_path('node_modules/simple-icons/icons/');
    }

    public function boot(): void
    {
        view()->composer('*', function($view) {
            $view->with('si', function(string $icon, ?string $class = null, int $size = 24, array $attrs = []) {
                return $this->getIcon($icon, $class, $size, $attrs);
            });
        });
    }

    private function getIcon(string $icon, ?string $class = null, int $size = 24, array $attrs = []): string
    {
        $attributes = array_merge($this->defaultAttributes, $attrs);

        if ($class) {
            $attributes['class'] = $class;
        }

        $attributes['width'] = $size;
        $attributes['height'] = $size;
        $attributes['viewBox'] = '0 0 24 24';

        $iconPath = $this->getIconPath($icon);

        return $this->generateSvg($iconPath, $attributes);
    }

    private function getIconPath(string $icon): string
    {
        $iconFile = $this->iconsPath . strtolower($icon) . '.svg';
        if (!file_exists($iconFile)) {
            return '';
        }

        $svg = file_get_contents($iconFile);
        preg_match('/<path d="([^"]+)"/', $svg, $matches);
        return $matches[1] ?? '';
    }

    private function generateSvg(string $path, array $attributes): string
    {
        $attrs = '';
        foreach ($attributes as $key => $value) {
            $attrs .= " {$key}=\"{$value}\"";
        }

        return "<svg{$attrs}><path d=\"{$path}\"/></svg>";
    }
}
