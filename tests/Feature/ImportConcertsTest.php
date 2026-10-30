<?php

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    Artisan::call('migrate');
    Carbon::setTestNow('2026-10-30 12:00:00');
});

function blackwoodEventPage(string $date, string $times): string
{
    return <<<HTML
        <script type="application/ld+json" class="yoast-schema-graph">{"@context":"https://schema.org","@graph":[{"@type":"WebPage","datePublished":"2026-01-01T09:00:00+00:00"}]}</script>
        <div class="mec-single-event-date"><dl><dd><abbr class="mec-events-abbr"><span class="mec-start-date-label">Nov 21 2026</span></abbr></dd></dl></div>
        <div class="mec-single-event-time"><i class="mec-sl-clock "></i><h3 class="mec-time">Time</h3><dl><dd><abbr class="mec-events-abbr">{$times}</abbr></dd></dl></div>
        <script type="application/ld+json">{
            "@context": "http://schema.org",
            "@type": "Event",
            "startDate": "{$date}",
            "endDate": "{$date}",
            "location":
            {
                "@type": "Place",

                "name": "St George\'s Church",
                "address": "14 Church Ln, Ticknall, Derby DE73 7JU"
            }
        }</script>
        HTML;
}

function fakeConcertSources(string $blackwoodDate, string $blackwoodTimes, string $cirrusStart): void
{
    $mecEvent = fn (int $id, string $slug) => [
        'id' => $id,
        'slug' => $slug,
        'date' => '2026-01-01T09:00:00',
        'link' => "https://blackwoodclarinets.co.uk/events/{$slug}/",
        'title' => ['rendered' => 'Autumn &amp; Winter'],
        'content' => ['rendered' => '<p>We are very much looking forward to it.</p>'],
    ];

    Http::fake([
        'blackwoodclarinets.co.uk/wp-json/*' => Http::response([$mecEvent(1, 'autumn'), $mecEvent(2, 'spring')]),
        'blackwoodclarinets.co.uk/events/autumn/' => Http::response(blackwoodEventPage($blackwoodDate, $blackwoodTimes)),
        'blackwoodclarinets.co.uk/events/spring/' => Http::response(blackwoodEventPage('2026-04-01', '7:30 pm - 9:30 pm')),
        'cirrusquartet.uk/*' => Http::response(['events' => [[
            'id' => 7,
            'slug' => 'cirrus-live',
            'title' => 'Cirrus Live',
            'description' => '<p>Quartets</p>',
            'start_date' => $cirrusStart,
            'end_date' => '2026-12-01 21:00:00',
            'timezone' => 'Europe/London',
            'image' => false,
            'venue' => ['venue' => 'The Studio', 'city' => 'Leeds'],
            'website' => '',
        ]]]),
    ]);
}

it('imports concerts at their scheduled time and skips past ones', function () {
    fakeConcertSources('2026-11-21', '7:00 pm - 10:00 pm', '2026-12-01 19:00:00');

    Artisan::call('concerts:import');

    expect(Concert::pluck('external_id')->sort()->values()->all())->toBe(['blackwood_1', 'cirrus_7']);

    $blackwood = Concert::firstWhere('external_id', 'blackwood_1');
    expect($blackwood->start_datetime->format('Y-m-d H:i'))->toBe('2026-11-21 19:00')
        ->and($blackwood->end_datetime->format('H:i'))->toBe('22:00')
        ->and($blackwood->location)->toBe("St George's Church")
        ->and($blackwood->name)->toBe('Autumn & Winter');

    $cirrus = Concert::firstWhere('external_id', 'cirrus_7');
    expect($cirrus->start_datetime->format('Y-m-d H:i'))->toBe('2026-12-01 19:00')
        ->and($cirrus->location)->toBe('The Studio, Leeds')
        ->and($cirrus->ticket_url)->toBeNull();
});

it('updates concerts that have been rescheduled', function () {
    fakeConcertSources('2026-11-21', '7:00 pm - 10:00 pm', '2026-12-01 19:00:00');
    Artisan::call('concerts:import');

    Http::swap(new Illuminate\Http\Client\Factory);
    fakeConcertSources('2026-11-28', '6:30 pm - 9:00 pm', '2026-12-01 20:00:00');
    Artisan::call('concerts:import');

    expect(Concert::count())->toBe(2)
        ->and(Concert::firstWhere('external_id', 'blackwood_1')->start_datetime->format('Y-m-d H:i'))->toBe('2026-11-28 18:30')
        ->and(Concert::firstWhere('external_id', 'cirrus_7')->start_datetime->format('H:i'))->toBe('20:00');
});

it('removes upcoming concerts that a source no longer lists, keeping manual ones', function () {
    fakeConcertSources('2026-11-21', '7:00 pm - 10:00 pm', '2026-12-01 19:00:00');
    Artisan::call('concerts:import');
    Concert::create(['name' => 'Solo recital', 'slug' => 'solo', 'start_datetime' => '2026-12-05 19:00:00', 'location' => 'Home', 'ensemble' => 'Blackwood Clarinets']);

    Http::swap(new Illuminate\Http\Client\Factory);
    Http::fake([
        'blackwoodclarinets.co.uk/wp-json/*' => Http::response([[
            'id' => 2,
            'slug' => 'spring',
            'link' => 'https://blackwoodclarinets.co.uk/events/spring/',
            'title' => ['rendered' => 'Spring'],
            'content' => ['rendered' => ''],
        ]]),
        'blackwoodclarinets.co.uk/events/spring/' => Http::response(blackwoodEventPage('2026-04-01', '')),
        'cirrusquartet.uk/*' => Http::response(['events' => []]),
    ]);
    Artisan::call('concerts:import');

    expect(Concert::pluck('slug')->sort()->values()->all())->toBe(['cirrus-live', 'solo']);
});

it('clears all concerts', function () {
    fakeConcertSources('2026-11-21', '7:00 pm - 10:00 pm', '2026-12-01 19:00:00');
    Artisan::call('concerts:import');

    expect(Artisan::call('concerts:clear', ['--force' => true]))->toBe(0)
        ->and(Concert::count())->toBe(0);
});

it('shows the time as TBC when a Blackwood page gives no time', function () {
    fakeConcertSources('2026-11-21', '', '2026-12-01 19:00:00');

    Artisan::call('concerts:import');

    $blackwood = Concert::firstWhere('external_id', 'blackwood_1');
    expect($blackwood->start_datetime->format('Y-m-d H:i'))->toBe('2026-11-21 00:00')
        ->and($blackwood->end_datetime)->toBeNull()
        ->and($blackwood->formatted_time_range)->toBe('Time TBC');
});
