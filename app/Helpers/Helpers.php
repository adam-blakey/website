<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class Helpers
{
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
