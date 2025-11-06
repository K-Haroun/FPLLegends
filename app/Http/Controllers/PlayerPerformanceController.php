<?php

namespace App\Http\Controllers;

use App\Models\Gameweek;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class PlayerPerformanceController extends Controller
{
    public function topPlayersOfWeek()
    {
        // Get the latest finished gameweek
        $latestGameweek = Gameweek::where('is_finished', true)
            ->orderByDesc('id')
            ->first();

        if (!$latestGameweek) {
            return response()->json(['message' => 'No finished gameweeks found'], 404);
        }

        // Query top players by position
        $topPlayers = Player::select('players.*', 'player_performances.total_points')
            ->join('player_performances', 'players.id', '=', 'player_performances.player_id')
            ->where('player_performances.gameweek_id', $latestGameweek->id)
            ->orderByDesc('player_performances.total_points')
            ->get()
            ->groupBy('position')
            ->map(function ($group) {
                return $group->first();
            });

        $response = [
            'gameweek' => $latestGameweek->id,
            'top_players' => $topPlayers->map(function ($player) {
                return [
                    'id' => $player->id,
                    'name' => $player->web_name ?? $player->name,
                    'team' => $player->team->name ?? null,
                    'position' => $player->position,
                    'total_points' => $player->total_points,
                    'price' => $player->now_cost ?? null,
                ];
            })
        ];

        return Inertia::render('Stats', [
            'topPlayers' => $response
        ]);
    }
}
