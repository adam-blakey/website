<?php

namespace App\Console\Commands;

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use DOMDocument;

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

        // Import Blackwood Clarinets concerts
        $this->importBlackwoodConcerts();

        // Import Cirrus Clarinet Quartet concerts
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
            // In a real implementation, you would fetch from the actual API endpoint
            // For this example, we'll use a mock response
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
        }
    }

    /**
     * Import concerts from Cirrus Clarinet Quartet.
     */
    private function importCirrusConcerts()
    {
        $this->info('Importing Cirrus Clarinet Quartet concerts...');

        try {
            // In a real implementation, you would fetch from the actual API endpoint
            // For this example, we'll use a mock response
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
        }
    }

    /**
     * Process a Blackwood Clarinets concert.
     */
    private function processBlackwoodConcert($event)
    {
        $externalId = 'blackwood_' . $event['id'];

        // Check if concert already exists
        $existingConcert = Concert::where('external_id', $externalId)->first();
        if ($existingConcert) {
            $this->info("Blackwood concert already exists: {$event['title']['rendered']}");
            return;
        }

        // Extract concert details
        $title = html_entity_decode($event['title']['rendered'], ENT_QUOTES | ENT_HTML5);
        $slug = $event['slug'];
        $content = $event['content']['rendered'];
        $startDate = Carbon::parse($event['date']);

        // Extract location and time from content
        $location = $this->extractLocationFromBlackwoodContent($content);
        $timeInfo = $this->extractTimeFromBlackwoodContent($content);

        // If we found a specific time in the content, update the start date
        if ($timeInfo['time']) {
            $timeParts = explode(':', $timeInfo['time']);
            $hour = (int)$timeParts[0];
            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;

            // Adjust for PM if needed
            if ($timeInfo['isPM'] && $hour < 12) {
                $hour += 12;
            }

            $startDate->setTime($hour, $minute);
        }

        // End time is typically 1.5 hours after start for concerts
        $endDate = clone $startDate;
        $endDate->addHours(1)->addMinutes(30);

        // Extract poster URL
        $posterUrl = null;
        if (isset($event['_links']['wp:featuredmedia'][0]['href'])) {
            $posterUrl = $this->extractPosterUrlFromBlackwood($event);
        }

        // Extract ticket URL from content
        $ticketUrl = $this->extractTicketUrlFromBlackwoodContent($content);

        // Clean up description
        $description = $this->cleanHtmlContent($content);

        // Create the concert
        Concert::create([
            'name' => $title,
            'slug' => $slug,
            'description' => $description,
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'location' => $location,
            'poster_url' => $posterUrl,
            'ticket_url' => $ticketUrl,
            'ensemble' => 'Blackwood Clarinets',
            'external_id' => $externalId,
        ]);

        $this->info("Imported Blackwood concert: {$title}");
    }

    /**
     * Process a Cirrus Clarinet Quartet concert.
     */
    private function processCirrusConcert($event)
    {
        $externalId = 'cirrus_' . $event['id'];

        // Check if concert already exists
        $existingConcert = Concert::where('external_id', $externalId)->first();
        if ($existingConcert) {
            $this->info("Cirrus concert already exists: {$event['title']['rendered']}");
            return;
        }

        // Extract concert details
        $title = html_entity_decode($event['title']['rendered'], ENT_QUOTES | ENT_HTML5);
        $slug = $event['slug'];
        $content = $event['content']['rendered'];

        // Extract start and end times from meta data
        $startDate = Carbon::parse($event['meta']['_EventStartDate']);
        $endDate = isset($event['meta']['_EventEndDate']) ? Carbon::parse($event['meta']['_EventEndDate']) : null;

        // Extract location
        $location = $this->extractLocationFromCirrusContent($event);

        // Extract poster URL
        $posterUrl = null;
        if (isset($event['_links']['wp:featuredmedia'][0]['href'])) {
            $posterUrl = $this->extractPosterUrlFromCirrus($event);
        }

        // Extract ticket URL
        $ticketUrl = $event['meta']['_EventURL'] ?? null;

        // Clean up description
        $description = $this->cleanHtmlContent($content);

        // Create the concert
        Concert::create([
            'name' => $title,
            'slug' => $slug,
            'description' => $description,
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'location' => $location,
            'poster_url' => $posterUrl,
            'ticket_url' => $ticketUrl,
            'ensemble' => 'Cirrus Clarinet Quartet',
            'external_id' => $externalId,
        ]);

        $this->info("Imported Cirrus concert: {$title}");
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
        // In a real implementation, you would make an API request to get the media details
        // For this example, we'll check if the URL is in the event data
        if (isset($event['yoast_head_json']['og_image'][0]['url'])) {
            return $event['yoast_head_json']['og_image'][0]['url'];
        }

        return null;
    }

    /**
     * Extract location from Cirrus event.
     */
    private function extractLocationFromCirrusContent($event)
    {
        // Check if venue information is available in meta
        if (isset($event['meta']['_VenueAddress']) && !empty($event['meta']['_VenueAddress'])) {
            $location = $event['meta']['_VenueAddress'];

            if (isset($event['meta']['_VenueCity']) && !empty($event['meta']['_VenueCity'])) {
                $location .= ', ' . $event['meta']['_VenueCity'];
            }

            return $location;
        }

        // Try to extract from content
        $content = $event['content']['rendered'];
        if (preg_match('/<div class="tribe-block__venue__name">\s*<h3>([^<]+)<\/h3>/i', $content, $matches)) {
            return trim($matches[1]);
        }

        return 'TBA';
    }

    /**
     * Extract poster URL from Cirrus featured media.
     */
    private function extractPosterUrlFromCirrus($event)
    {
        // In a real implementation, you would make an API request to get the media details
        // For this example, we'll assume the URL is in the event data
        if (isset($event['_embedded']['wp:featuredmedia'][0]['source_url'])) {
            return $event['_embedded']['wp:featuredmedia'][0]['source_url'];
        }

        return null;
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
            return [];
        }
    }

    /**
     * Get Cirrus Clarinet Quartet concerts data.
     */
    private function getCirrusConcertsData()
    {
        try {
            $response = Http::get('https://cirrusquartet.uk/wp-json/wp/v2/tribe_events');

            if ($response->successful()) {
                return $response->json();
            } else {
                $this->error('Failed to fetch Cirrus concerts: HTTP ' . $response->status());
                return [];
            }
        } catch (\Exception $e) {
            $this->error('Exception when fetching Cirrus concerts: ' . $e->getMessage());
            return [];
        }
    }
}
