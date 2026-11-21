<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Concert;
use App\Models\Contact;
use App\Models\MusicNowPlaying;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class OverviewController extends Controller
{
    public const TREND_DAYS = 14;

    public function __invoke()
    {
        $posts = BlogPost::orderByDesc('published_at')->get(['id', 'title', 'slug']);
        $visits = $this->visits($posts);

        return view('dashboard.index', [
            'visits' => $visits,
            'mostVisitedPosts' => $visits ? $posts->sortByDesc(fn (BlogPost $post) => $visits['posts'][$post->id] ?? 0)->take(10) : collect(),
            'postCount' => $posts->count(),
            'upcomingConcertCount' => Concert::upcoming()->count(),
            'contactCount' => Contact::count(),
            'latestTrack' => MusicNowPlaying::latest('played_at')->first(),
            'blogMediaBytes' => $this->directoryBytes(public_path('storage/blog-media')),
            'resizedImageBytes' => $this->directoryBytes(base_path('resources/img/sizes')),
        ]);
    }

    private function visits(Collection $posts): ?array
    {
        $code = config('services.goatcounter.code');

        if (! $code) {
            return null;
        }

        return Cache::get('dashboard:visits') ?? $this->fetchVisits("https://{$code}.goatcounter.com/counter/", $posts);
    }

    private function fetchVisits(string $counterUrl, Collection $posts): ?array
    {
        $days = collect(range(self::TREND_DAYS - 1, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->toDateString());

        $responses = Http::pool(fn (Pool $pool) => [
            $pool->as('total')->timeout(5)->get($counterUrl . 'TOTAL.json'),
            ...$days->map(fn (string $day) => $pool->as("day:{$day}")->timeout(5)->get($counterUrl . 'TOTAL.json', ['start' => $day, 'end' => $day]))->all(),
            ...$posts->map(fn (BlogPost $post) => $pool->as("post:{$post->id}")->timeout(5)->get($counterUrl . ltrim($post->url, '/') . '.json'))->all(),
        ]);

        $total = self::count($responses['total']);

        if ($total === null) {
            return null;
        }

        $visits = [
            'total' => $total,
            'daily' => $days->mapWithKeys(fn (string $day) => [$day => self::count($responses["day:{$day}"]) ?? 0])->all(),
            'posts' => $posts->mapWithKeys(fn (BlogPost $post) => [$post->id => self::count($responses["post:{$post->id}"]) ?? 0])->all(),
        ];

        Cache::put('dashboard:visits', $visits, now()->addHour());

        return $visits;
    }

    private static function count(mixed $response): ?int
    {
        if (! $response instanceof Response) {
            return null;
        }

        if ($response->notFound()) {
            return 0;
        }

        return $response->successful() ? (int) preg_replace('/\D/', '', (string) $response->json('count')) : null;
    }

    private function directoryBytes(string $directory): int
    {
        return File::isDirectory($directory)
            ? collect(File::allFiles($directory))->sum(fn ($file) => $file->getSize())
            : 0;
    }
}
