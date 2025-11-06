<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SportMonksService
{
    protected string $base;
    protected string $token;

    public function __construct()
    {
        $this->base = rtrim(config('services.sportmonks.base'), '/');
        $this->token = config('services.sportmonks.token');
    }

    /**
     * Generic GET request handler with caching, retry and logging.
     * Ensures api_token is always sent as a query parameter.
     */
    private function get(string $endpoint, string $label, array $params = []): array
    {
        // Ensure api_token is passed as query param (SportMonks expects ?api_token=...)
        $params = array_merge(['api_token' => $this->token], $params);

        $cacheKey = 'sportmonks.' . md5($endpoint . json_encode($params));

        return Cache::remember($cacheKey, 60 * 60 * 12, function () use ($endpoint, $label, $params) {
            try {
                $url = "{$this->base}{$endpoint}";

                $response = Http::retry(3, 200)
                    ->timeout(15)
                    ->withHeaders([
                        // Header auth is optional; we still send Accept
                        'Accept' => 'application/json',
                    ])
                    ->get($url, $params)
                    ->throw()
                    ->json();

                return $response ?? [];
            } catch (\Exception $e) {
                Log::error("{$label} request failed", [
                    'message' => $e->getMessage(),
                    'endpoint' => $endpoint,
                ]);
                return [];
            }
        });
    }

    /**
     * Get a single player by ID.
     */
    public function player(int $playerId): array
    {
        return $this->get("/players/{$playerId}", "SportMonks Player {$playerId}", [
            'include' => 'nationality',
            'select' => 'id,name',
        ]);
    }

    /**
     * Search players by name (includes team & country).
     * Uses rawurlencode to avoid issues with spaces/special chars.
     */
    public function searchPlayer(string $name): array
    {
        $encoded = rawurlencode($name);

        // Note: endpoint is '/players/search/{query}'
        return $this->get("/players/search/{$encoded}", "SportMonks Player Search", [
            'include' => 'nationality',
            'select' => 'id,name',
        ]);
    }

    /**
     * Get all Premier League players.
     * League ID 8 = Premier League (SportMonks default)
     */
    public function premierLeaguePlayers(): array
    {
        return $this->get("/players", "SportMonks Premier League Players", [
            'filter[league_id]' => 8,
            'include' => 'team,country',
            'select' => 'id,display_name,firstname,lastname,height,number,team_id,country_id',
        ]);
    }

    /**
     * Extract nationality, height (m), and squad number for a player.
     */
    public function getPlayerBio(string $webName, ?string $firstName = null, ?string $secondName = null): ?array
    {
        $searchName = $firstName ? "{$firstName} {$secondName}" : $webName;

        $data = $this->searchPlayer($searchName);

        dd($data);

        if (empty($data['data']) || !is_array($data['data'])) {
            return null;
        }

        // Try to match by name (case-insensitive substring)
        $player = collect($data['data'])->first(function ($p) use ($webName, $firstName) {
            return str_contains(strtolower($p['display_name'] ?? ''), strtolower($webName))
                || ($firstName && str_contains(strtolower($p['firstname'] ?? ''), strtolower($firstName)));
        });

        if (!$player) {
            // If no good match, just pick the first result as a fallback
            $player = $data['data'][0] ?? null;
            if (!$player) {
                return null;
            }
        }

        // height from SportMonks is usually in cm; convert to metres if present
        $height = isset($player['height']) && is_numeric($player['height'])
            ? (float) ($player['height'] / 100)
            : null;

        $nationality = $player['country']['data']['name'] ?? $player['country']['name'] ?? $player['nationality'] ?? null;
        $squadNumber = $player['number'] ?? $player['shirt_number'] ?? null;

        return [
            'height' => $height,
            'nationality' => $nationality,
            'squad_number' => $squadNumber,
        ];
    }
}
