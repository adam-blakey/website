<?php

namespace App\Console\Commands;

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportConcerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'concerts:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import upcoming concerts from Blackwood Clarinets and Cirrus Clarinet Quartet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting concert import...');

        $this->importBlackwoodConcerts();

        $this->importCirrusConcerts();

        $this->info('Concert import completed successfully!');

        return 0;
    }

    /**
     * Import concerts from Blackwood Clarinets.
     */
    private function importBlackwoodConcerts()
    {
        $this->info('Importing Blackwood Clarinets concerts...');

        try {
            $response = $this->getBlackwoodConcertsData();

            if (empty($response)) {
                $this->warn('No Blackwood concerts found.');
                return;
            }

            foreach ($response as $event) {
                $this->processBlackwoodConcert($event);
            }

            $this->pruneMissingConcerts('blackwood_', array_column($response, 'id'));

            $this->info('Blackwood Clarinets concerts imported successfully.');
        } catch (\Exception $e) {
            $this->error('Error importing Blackwood Clarinets concerts: ' . $e->getMessage());
            report($e);
        }
    }

    /**
     * Import concerts from Cirrus Clarinet Quartet.
     */
    private function importCirrusConcerts()
    {
        $this->info('Importing Cirrus Clarinet Quartet concerts...');

        try {
            $response = $this->getCirrusConcertsData();

            if (empty($response)) {
                $this->warn('No Cirrus concerts found.');
                return;
            }

            foreach ($response as $event) {
                $this->processCirrusConcert($event);
            }

            $this->pruneMissingConcerts('cirrus_', array_column($response, 'id'));

            $this->info('Cirrus Clarinet Quartet concerts imported successfully.');
        } catch (\Exception $e) {
            $this->error('Error importing Cirrus Clarinet Quartet concerts: ' . $e->getMessage());
            report($e);
        }
    }

    /**
     * Process a Blackwood Clarinets concert.
     */
    private function processBlackwoodConcert($event)
    {
        $title = html_entity_decode($event['title']['rendered'], ENT_QUOTES | ENT_HTML5);
        $page = Http::get($event['link']);
        $schema = $page->successful() ? $this->getEventSchema($page->body()) : null;

        if (! isset($schema['startDate'])) {
            $this->warn("No event schedule found for Blackwood concert: {$title}");
            return;
        }

        $content = $event['content']['rendered'];
        [$startTime, $endTime] = $this->extractTimeRangeFromBlackwoodPage($page->body()) + [null, null];
        $startDate = $this->toLocalTime(substr($schema['startDate'], 0, 10) . ' ' . $startTime);
        $endDate = $endTime ? $this->toLocalTime(substr($schema['endDate'] ?? $schema['startDate'], 0, 10) . ' ' . $endTime) : null;

        $this->saveConcert('blackwood_' . $event['id'], [
            'name' => $title,
            'slug' => $event['slug'],
            'description' => $this->cleanHtmlContent($content),
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'location' => $schema['location']['name'] ?? $this->extractLocationFromBlackwoodContent($content),
            'address' => $schema['location']['address'] ?? null,
            'poster_url' => $this->extractPosterUrlFromBlackwood($event),
            'ticket_url' => $this->extractTicketUrlFromBlackwoodContent($content) ?? $schema['offers']['url'] ?? null,
            'event_url' => $event['link'],
            'ensemble' => 'Blackwood Clarinets',
        ]);
    }

    /**
     * Process a Cirrus Clarinet Quartet concert.
     */
    private function processCirrusConcert($event)
    {
        $this->saveConcert('cirrus_' . $event['id'], [
            'name' => html_entity_decode($event['title'], ENT_QUOTES | ENT_HTML5),
            'slug' => $event['slug'],
            'description' => $this->cleanHtmlContent($event['description']),
            'start_datetime' => $this->toLocalTime($event['start_date'], $event['timezone'] ?? null),
            'end_datetime' => $this->toLocalTime($event['end_date'], $event['timezone'] ?? null),
            'location' => $this->extractLocationFromCirrusVenue($event['venue']),
            'address' => implode(', ', array_filter([$event['venue']['address'] ?? null, $event['venue']['city'] ?? null, $event['venue']['zip'] ?? null])) ?: null,
            'poster_url' => $event['image']['url'] ?? null,
            'ticket_url' => $event['website'] ?: null,
            'event_url' => $event['url'],
            'ensemble' => 'Cirrus Clarinet Quartet',
        ]);
    }

    private function pruneMissingConcerts(string $source, array $ids)
    {
        $count = Concert::upcoming()
            ->where('external_id', 'like', $source . '%')
            ->whereNotIn('external_id', array_map(fn ($id) => $source . $id, $ids))
            ->delete();

        if ($count > 0) {
            $this->info("Removed {$count} upcoming concerts no longer listed by the source.");
        }
    }

    private function saveConcert(string $externalId, array $attributes)
    {
        if ($attributes['start_datetime']->isPast()) {
            $this->info("Skipping past concert: {$attributes['name']}");
            return;
        }

        $concert = Concert::updateOrCreate(['external_id' => $externalId], $attributes);

        $this->info(($concert->wasRecentlyCreated ? 'Imported' : 'Updated') . " {$concert->ensemble} concert: {$concert->name}");
    }

    private function getEventSchema(string $html): ?array
    {
        preg_match_all('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $html, $matches);

        foreach ($matches[1] as $json) {
            $data = json_decode(str_replace("\\'", "'", trim($json)), true);

            if (! is_array($data)) {
                continue;
            }

            foreach ($data['@graph'] ?? (array_is_list($data) ? $data : [$data]) as $item) {
                if (collect((array) ($item['@type'] ?? []))->contains(fn ($type) => str_ends_with($type, 'Event'))) {
                    return $item;
                }
            }
        }

        return null;
    }

    private function extractTimeRangeFromBlackwoodPage(string $html): array
    {
        preg_match('#mec-single-event-time.*?<abbr class="mec-events-abbr">([^<]*)</abbr>#s', $html, $timeBox);
        preg_match_all('/\d{1,2}:\d{2}\s*[ap]m/i', $timeBox[1] ?? '', $times);

        return $times[0];
    }

    private function toLocalTime(string $dateTime, ?string $timezone = null): Carbon
    {
        return Carbon::parse($dateTime, $timezone)->setTimezone(config('app.timezone'));
    }

    /**
     * Extract location from Blackwood content.
     */
    private function extractLocationFromBlackwoodContent($content)
    {
        // Look for location pattern in content (typically after 📍 emoji)
        if (preg_match('/📍\s*<strong>([^<]+)<\/strong>/i', $content, $matches)) {
            return trim($matches[1]);
        }

        // Fallback: look for any text that might be a location
        if (preg_match('/<strong>([^<]+(?:Hall|Church|Theatre|Center|Centre)[^<]*)<\/strong>/i', $content, $matches)) {
            return trim($matches[1]);
        }

        return 'TBA';
    }

    /**
     * Extract ticket URL from Blackwood content.
     */
    private function extractTicketUrlFromBlackwoodContent($content)
    {
        // Look for ticket URL in content
        if (preg_match('/href="([^"]+)"[^>]*>(?:[^<]*tickets|[^<]*book)[^<]*</i', $content, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Extract poster URL from Blackwood featured media.
     */
    private function extractPosterUrlFromBlackwood($event)
    {
        if (isset($event['yoast_head_json']['og_image'][0]['url'])) {
            return $event['yoast_head_json']['og_image'][0]['url'];
        }

        return null;
    }

    /**
     * Extract location from a Cirrus event venue.
     */
    private function extractLocationFromCirrusVenue($venue)
    {
        return implode(', ', array_filter([$venue['venue'] ?? null, $venue['city'] ?? null])) ?: 'TBA';
    }

    /**
     * Clean HTML content to get a plain description.
     */
    private function cleanHtmlContent($content)
    {
        // Remove HTML tags but preserve line breaks
        $content = strip_tags($content, '<p><br><ul><li>');

        // Convert HTML entities
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5);

        return $content;
    }

    /**
     * Get Blackwood Clarinets concerts data.
     */
    private function getBlackwoodConcertsData()
    {
        try {
            $response = Http::get('https://blackwoodclarinets.co.uk/wp-json/wp/v2/mec-events', ['per_page' => 100]);

            if ($response->successful()) {
                return $response->json();
            } else {
                $this->error('Failed to fetch Blackwood concerts: HTTP ' . $response->status());
                return [];
            }
        } catch (\Exception $e) {
            $this->error('Exception when fetching Blackwood concerts: ' . $e->getMessage());
            report($e);
            return [];
        }
    }

    /**
     * Get Cirrus Clarinet Quartet concerts data.
     */
    private function getCirrusConcertsData()
    {
        try {
            $response = Http::get('https://cirrusquartet.uk/wp-json/tribe/events/v1/events', ['per_page' => 50]);

            if ($response->successful()) {
                return $response->json('events') ?? [];
            } else {
                $this->error('Failed to fetch Cirrus concerts: HTTP ' . $response->status());
                return [];
            }
        } catch (\Exception $e) {
            $this->error('Exception when fetching Cirrus concerts: ' . $e->getMessage());
            report($e);
            return [];
        }
    }
}
