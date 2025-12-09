<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserTeamController extends Controller
{
    public function index() {
        $players = Player::with(['performances' => function ($query) {
            $query->orderBy('gameweek_id', 'desc');
        }]);

        return Inertia::render('Dashboard', [
            'all_players' => PlayerResource::collection(Player::all())
        ]);
    }
}
