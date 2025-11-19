<?php

namespace App\Http\Controllers;

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
            'allPlayers' => PlayerResource::collection(Player::all())
        ]);
    }

    public function show(Player $player) {
        $player->load(['performances' => function ($query) {
            $query->orderBy('gameweek_id', 'desc');
        }]);

        return Inertia::render("Players/Show", [
            'player' => PlayerResource::make($player)
        ]);
    }
}
