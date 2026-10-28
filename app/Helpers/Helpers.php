<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class Helpers
{
    // Stolen from https://stackoverflow.com/a/22964930
    public static function isExternalUrl(string $url): bool
    {
        $components = parse_url($url);
        return !empty($components['host']) && strcasecmp($components['host'], config('app.url')) !== 0;
    }

    public static function getPageLink($tabs, $currentTab): ?string
    {
        if (is_null($currentTab)) {
            return null;
        }

        // TODO: Messy.
        if ($currentTab == 'editor') {
            return 'editor';
        }

        if (Arr::has($tabs, $currentTab)) {
            return Arr::get($tabs, $currentTab)->path;
        }

        return null;
    }

    public static function getPageName($tabs, $currentTab): ?string
    {
        if (is_null($currentTab)) {
            return null;
        }

        // TODO: Messy.
        if ($currentTab == 'editor') {
            return 'Editor';
        }

        if (Arr::has($tabs, $currentTab)) {
            return Arr::get($tabs, $currentTab)->name;
        }

        return null;
    }
}
