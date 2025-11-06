<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FplService
{
    protected string $base;

    public function __construct()
    {
        $this->base = config('services.fpl.base') ?? 'https://fantasy.premierleague.com';
    }

    public function bootstrap(): array
    {

        return $this->get('/api/bootstrap-static/', 'FPL bootstrap');
    }

    public function fixtures(): array
    {

        return $this->get('/api/fixtures/', 'FPL fixtures');
    }

    public function gameweekLive(int $gameweekId): array
    {
        return $this->get('/api/event/'.$gameweekId.'/live/', 'Gameweek Player Performances');
    }

    public function elementSummary(int $playerId): array
    {

        return $this->get("/api/element-summary/{$playerId}/", 'FPL element summary');
    }

    private function get(string $endpoint, string $label): array
    {

        $cacheKey = 'fpl.'.str_replace('/', '.', trim($endpoint, '/'));

        return Cache::remember($cacheKey, 60 * 5, function () use ($endpoint, $label) {
            try {
                return Http::retry(3, 100)
                    ->timeout(10)
                    ->get($this->base.$endpoint)
                    ->throw()
                    ->json();
            } catch (\Exception $e) {
                Log::error("{$label} request failed", ['message' => $e->getMessage()]);

                return [];
            }
        });
    }
}
