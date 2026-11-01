<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mdi\Mdi;

class MaterialDesignIconsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Mdi::withDefaultAttributes([
            'fill' => 'currentColor',
            'stroke' => 'none',
            'role' => 'img',
        ]);
        $iconsPath = base_path('/node_modules/@mdi/svg/svg/');
        if (!is_dir($iconsPath)) {
            throw new \RuntimeException("Material Design Icons not found at {$iconsPath}. Run npm ci.");
        }
        Mdi::withIconsPath($iconsPath);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function($view) {
            $view->with('mdi', function(string $icon, ?string $class = null, int $size = 24, array $attrs = []) {
                return $this->getIcon($icon, $class, $size, $attrs);
            });
        });
    }

    private function getIcon(string $icon, ?string $class = null, int $size = 24, array $attrs = [])
    {
        $attrs = array_merge($attrs, ['aria-label' => $icon . ' icon', 'role' => 'img']);

        return Mdi::mdi($icon, $class, $size, $attrs);
    }
}
