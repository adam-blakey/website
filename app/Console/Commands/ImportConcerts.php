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
        $schema = $this->getEventSchema($event['link']);

        if (! isset($schema['startDate'])) {
            $this->warn("No event schedule found for Blackwood concert: {$title}");
            return;
        }

        $content = $event['content']['rendered'];
        $startDate = $this->toLocalTime($schema['startDate']);
        $timeInfo = $this->extractTimeFromBlackwoodContent($content);

        if (! $this->hasTime($schema['startDate']) && $timeInfo['time']) {
            $timeParts = explode(':', $timeInfo['time']);
            $hour = (int)$timeParts[0];
            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;

            if ($timeInfo['isPM'] && $hour < 12) {
                $hour += 12;
            }

            $startDate->setTime($hour, $minute);
        }

        $endDate = isset($schema['endDate']) && $this->hasTime($schema['endDate'])
            ? $this->toLocalTime($schema['endDate'])
            : $startDate->copy()->addHours(1)->addMinutes(30);

        $this->saveConcert('blackwood_' . $event['id'], [
            'name' => $title,
            'slug' => $event['slug'],
            'description' => $this->cleanHtmlContent($content),
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'location' => $schema['location']['name'] ?? $this->extractLocationFromBlackwoodContent($content),
            'poster_url' => $this->extractPosterUrlFromBlackwood($event),
            'ticket_url' => $this->extractTicketUrlFromBlackwoodContent($content) ?? $schema['offers']['url'] ?? null,
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
            'poster_url' => $event['image']['url'] ?? null,
            'ticket_url' => $event['website'] ?: null,
            'ensemble' => 'Cirrus Clarinet Quartet',
        ]);
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

    private function getEventSchema(string $url): ?array
    {
        $response = Http::get($url);

        if (! $response->successful()) {
            $this->error("Failed to fetch {$url}: HTTP " . $response->status());
            return null;
        }

        preg_match_all('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $response->body(), $matches);

        foreach ($matches[1] as $json) {
            $data = json_decode(trim($json), true);

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

    private function hasTime(string $dateTime): bool
    {
        return str_contains($dateTime, 'T') || str_contains($dateTime, ':');
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
     * Extract time information from Blackwood content.
     */
    private function extractTimeFromBlackwoodContent($content)
    {
        $result = ['time' => null, 'isPM' => false];

        // Look for time pattern in content (typically after ⏰ emoji)
        if (preg_match('/⏰\s*<strong>([0-9]{1,2}:[0-9]{2})\s*(AM|PM)?<\/strong>/i', $content, $matches)) {
            $result['time'] = $matches[1];
            $result['isPM'] = isset($matches[2]) && strtoupper($matches[2]) === 'PM';
            return $result;
        }

        // Alternative pattern
        if (preg_match('/<strong>([0-9]{1,2}:[0-9]{2})\s*(AM|PM)?<\/strong>/i', $content, $matches)) {
            $result['time'] = $matches[1];
            $result['isPM'] = isset($matches[2]) && strtoupper($matches[2]) === 'PM';
            return $result;
        }

        return $result;
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
            $response = Http::get('https://blackwoodclarinets.co.uk/wp-json/wp/v2/mec-events');

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
