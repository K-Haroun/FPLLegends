<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Player;
use App\Models\PlayerPerformance;
use Inertia\Inertia;
use App\Http\Resources\PlayerResource;

class PlayerController extends Controller
{
    public function index() {
        $players = Player::with(['performances' => function ($query) {
            $query->orderBy('gameweek_id', 'desc');
        }])->paginate(100);

        return Inertia::render('Players/Index', [
            'players' => PlayerResource::collection($players),
            'allPlayers' => PlayerResource::collection(Player::all()),
        ]);
    }

    public function show(Player $player) {
        $player->load([
            'performances' => function ($query) {
            $query->orderBy('gameweek_id', 'desc');
        }]);


        return Inertia::render("Players/Show", [
            'player' => PlayerResource::make($player),
            'fixtures' => $player->performances->map(function ($performance) {
                return [
                    'gameweek_id' => $performance->gameweek_id,
                    'total_points' => $performance->total_points,
                    'fixture' => [
                        'id' => $performance->fixture->id,
                        'home_team' => $performance->fixture->homeTeam,
                        'away_team' => $performance->fixture->awayTeam,
                        'home_score' => $performance->fixture->team_h_score,
                        'away_score' => $performance->fixture->team_a_score,
                        'kickoff' => $performance->fixture->kickoff_time,
                    ],
                ];
            }),
            'season_stats' => $this->totalStats($player)
        ]);
    }

    private function totalStats(Player $player) {
        $totalPoints = 0;
        $totalGoals = 0;
        $totalAssists = 0;

        foreach ($player->performances as $p) {
            $totalPoints += $p->total_points;
            $totalGoals += $p->goals_scored;
            $totalAssists += $p->assists;
        }

        return [
            'season_points' => $totalPoints,
            'season_goals' => $totalGoals,
            'season_assists' => $totalAssists,
        ];
    }
}
