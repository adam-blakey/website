<?php

namespace App\Providers;

use App\Models\Award;
use App\Models\MusicNowPlaying;
use App\Models\ProgrammingTime;
use App\Models\TabNavLink;
use App\Models\Talk;
use App\Models\TimelineEntry;
use App\Models\TopMenuEntry;
use App\Models\TopMenuSubEntry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $tabEntries = [
            'welcome' => new TabNavLink(['name' => 'Welcome', 'path' => 'welcome', 'icon' => 'home', 'color' => 'text-blue-400']),
            'about' => new TabNavLink(['name' => 'About me', 'path' => 'about', 'icon' => 'account', 'color' => 'text-violet-400']),
            'interests' => new TabNavLink(['name' => 'My Interests', 'path' => 'interests', 'icon' => 'heart', 'color' => 'text-rose-400']),
            'now' => new TabNavLink(['name' => 'Right now', 'path' => 'now', 'icon' => 'pulse', 'color' => 'text-cyan-400']),
            'blog' => new TabNavLink(['name' => 'Blog', 'path' => 'blog.index', 'icon' => 'post', 'color' => 'text-amber-400']),
            'contact' => new TabNavLink(['name' => 'Contact me', 'path' => 'contact', 'icon' => 'email', 'color' => 'text-emerald-400'])
        ];
        $topLevelPath = explode('/', request()->path())[0];
        $active = request()->is('/') ? 'welcome' : $topLevelPath;

        $taggedVersion = ltrim(config('app.version'), 'v');
        try {
            $latestTrack = MusicNowPlaying::where('played_at', '>', now()->addMinutes(-5))
                ->latest('played_at')
                ->first();
        } catch (\Exception $e) {
            $latestTrack = null;
        }

        try {
            $latestProgrammingTime = ProgrammingTime::getLatest();
            $programmingTime = $latestProgrammingTime ? $latestProgrammingTime->time_text : null;
        } catch (\Exception $e) {
            $programmingTime = null;
        }

        $programmingTimeUrl = env('WAKAPI_LEADERBOARD_URL');

        $topMenuEntries = [
            new TopMenuEntry([
                'name' => 'File',
                'subEntries' => [
                    new TopMenuSubEntry(['name' => 'New File', 'url' => 'editor', 'icon' => 'file-plus']),
                    new TopMenuSubEntry(['name' => 'Save File', 'url' => 'javascript:window.saveFile()', 'icon' => 'content-save', 'enabled' => request()->is('editor')]),
                ]
            ]),
            new TopMenuEntry([
                'name' => 'Socials',
                'subEntries' => collect(config('socials.platforms'))
                    ->map(fn ($platform) => new TopMenuSubEntry([
                        'name' => $platform['name'],
                        'url' => $platform['url'],
                        'icon' => $platform['icon'],
                        'use-simple-icons' => $platform['use-simple-icons'] ?? false,
                    ]))
                    ->values()
                    ->toArray()
            ]),
        ];

        $timelineEntries = $this->loadData('timeline', TimelineEntry::class);
        $awards = $this->loadData('awards', Award::class);
        $talks = $this->loadData('talks', Talk::class);

        View::share('layoutTabEntries', $tabEntries);
        View::share('layoutActiveTab', $active);
        View::share('layoutTopMenuEntries', $topMenuEntries);
        View::share('layoutTimelineEntries', $timelineEntries);
        View::share('layoutAwards', collect($awards)->where('featured', true));
        View::share('layoutTalks', collect($talks)->where('featured', true));
        View::share('layoutTaggedVersion', $taggedVersion);
        View::share('layoutLatestTrack', $latestTrack);
        View::share('layoutProgrammingTime', $programmingTime);
        View::share('layoutProgrammingTimeUrl', $programmingTimeUrl);
    }

    /**
     * Deserialise resources/data/{name}.json into an array of $class objects.
     */
    private function loadData(string $name, string $class): array
    {
        $rows = json_decode(file_get_contents(resource_path("data/{$name}.json")), true, flags: JSON_THROW_ON_ERROR);

        return array_map(fn (array $row) => new $class($row), $rows);
    }
}
