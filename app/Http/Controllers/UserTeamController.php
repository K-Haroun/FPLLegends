<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\UserTeam;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserTeamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userTeam = $user->userTeams()->with([
            'players',
            'goalkeepers',
            'defenders',
            'midfielders',
            'forwards'
        ])->first();

        $players = [
            'goalkeeper' => $userTeam?->goalkeepers ?? [],
            'defenders' => $userTeam?->defenders ?? [],
            'midfielders' => $userTeam?->midfielders ?? [],
            'forwards' => $userTeam?->forwards ?? [],
        ];

        return Inertia::render('Dashboard', [
            'all_players' => PlayerResource::collection(Player::all()),
            'user_team' => [
                'team' => $userTeam,
                'players' => $players,
            ],
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'players' => 'required|array',
            // 'players.*.id' => 'required|integer|exists:players,id',
        ]);

        $team = UserTeam::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
        ]);

        $pivotData = [];
        foreach ($validated['players'] as $position => $players) {
            foreach ($players as $player) {
                $pivotData[$player['id']] = ['position' => $position];
            }
        }

        $team->players()->attach($pivotData);

        return back()->with([
            'message' => 'Team created successfully',
            'user_team' => $team->load('players'),
        ]);
    }

}
