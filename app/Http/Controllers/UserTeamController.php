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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]*$/',
            'players' => 'required|array',
            'players.Goalkeeper' => 'nullable|array|max:1',
            'players.Goalkeeper.*.id' => 'required|integer|exists:players,id',
            'players.Defenders' => 'nullable|array|max:4',
            'players.Defenders.*.id' => 'required|integer|exists:players,id',
            'players.Midfielders' => 'nullable|array|max:4',
            'players.Midfielders.*.id' => 'required|integer|exists:players,id',
            'players.Forwards' => 'nullable|array|max:4',
            'players.Forwards.*.id' => 'required|integer|exists:players,id',
        ]);

        $team = UserTeam::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
        ]);

        $pivotData = [];
        foreach ($validated['players'] as $position => $players) {
            if (!empty($players)) { // Only process if there are players
                foreach ($players as $player) {
                    $pivotData[$player['id']] = ['position' => $position];
                }
            }
        }

        if (!empty($pivotData)) {
            $team->players()->attach($pivotData);
        }

        return back()->with([
            'message' => 'Team created successfully',
            'user_team' => $team->load('players'),
        ]);
    }

    public function destroy(UserTeam $team)
    {
        // Make sure user owns the team
        if ($team->user_id !== auth()->id()) {
            abort(403);
        }

        $team->delete();

        return back()->with('message', 'Team deleted successfully');
    }

}
