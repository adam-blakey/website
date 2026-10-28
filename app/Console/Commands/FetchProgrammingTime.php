<?php

namespace App\Console\Commands;

use App\Models\ProgrammingTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchProgrammingTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'programming-time:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch programming time data from Wakapi API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $url = config('programming-time.wakapi.url');
            $apiKey = config('programming-time.wakapi.key');

            $response = Http::get($url, [
                'api_key' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['grand_total']['text'])) {
                    $timeText = $data['data']['grand_total']['text'];
                    $totalSeconds = $data['data']['grand_total']['total_seconds'] ?? null;

                    ProgrammingTime::create([
                        'time_text' => $timeText,
                        'total_seconds' => $totalSeconds,
                        'fetched_at' => now(),
                    ]);

                    $this->info("Programming time updated: {$timeText}");
                } else {
                    $this->error('Invalid response format from Wakapi API');
                    Log::error('Invalid response format from Wakapi API', ['response' => $data]);
                }
            } else {
                $this->error('Failed to fetch data from Wakapi API');
                Log::error('Failed to fetch data from Wakapi API', ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            $this->error('Error fetching programming time: ' . $e->getMessage());
            Log::error('Error fetching programming time', ['exception' => $e->getMessage()]);
        }
    }
}
