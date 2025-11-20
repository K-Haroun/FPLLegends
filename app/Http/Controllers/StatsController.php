<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Gameweek;
use App\Models\Player;
use App\Models\PlayerPerformance;
use App\Models\Team;
use Carbon\Carbon;
use Inertia\Inertia;

class StatsController extends Controller
{
    public function index()
    {

        $allPlayers = $this->allPlayers();
        $topPlayers = $this->topPlayersOfWeek();
        $topTeams = $this->topTeamsOfWeek();
        $topTeamsAllWeeks = $this->topTeamsAllWeeks();

        return Inertia::render('Stats', [
            'allPlayers' => $allPlayers,
            'topPlayers' => $topPlayers,
            'topTeams' => $topTeams,
            'topTeamsAllWeeks' => $topTeamsAllWeeks,
        ]);
    }

    private function allPlayers()
    {
        $latestGameweek = $this->getCurrentGameWeek();

        $allPlayers = Player::select('players.*', 'player_performances.total_points')
            ->join('player_performances', 'players.id', '=', 'player_performances.player_id')
            ->where('player_performances.gameweek_id', $latestGameweek->id)
            ->get();

        return [
            'gameweek' => $latestGameweek->id,
            'all_players' => $allPlayers->map(function ($player) {
                return [
                    'id' => $player->id,
                    'fpl_id' => $player->fpl_id,
                    'name' => $player->web_name,
                    'team' => $player->team->name ?? null,
                    'team_id' => $player->team->id ?? null,
                    'position' => $player->position,
                    'total_points' => $player->total_points,
                    'price' => $player->now_cost ?? null,
                ];
            })->values(),
        ];
    }

    private function topPlayersOfWeek()
    {
        $latestGameweek = $this->getCurrentGameWeek();

        if (!$latestGameweek) {
            return [
                'gameweek' => null,
                'top_players' => [],
            ];
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

        return [
            'gameweek' => $latestGameweek->id,
            'top_players' => $topPlayers->map(function ($player) {
                return [
                    'id' => $player->id,
                    'fpl_id' => $player->fpl_id,
                    'name' => $player->web_name ?? $player->name,
                    'team' => $player->team->name ?? null,
                    'position' => $player->position,
                    'total_points' => $player->total_points,
                    'price' => $player->now_cost ?? null,
                ];
            })->values(),
        ];
    }

    private function topTeamsOfWeek()
    {
        $latestGameweek = $this->getCurrentGameWeek();
        $gameweeks = Gameweek::orderBy('fpl_id')->get();
        $upcomingFixtures = $this->upcomingFixtures()->with(['homeTeam', 'awayTeam'])->get();
        $teams = [];

        $dbTeams = Team::select('teams.id', 'teams.name')
            ->join('players', 'teams.id', '=', 'players.team_id')
            ->join('player_performances', 'players.id', '=', 'player_performances.player_id')
            ->where('player_performances.gameweek_id', $latestGameweek->id)
            ->groupBy('teams.id', 'teams.name')
            ->selectRaw('SUM(player_performances.total_points) as total_points')
            ->orderByDesc('total_points')
            ->take(5)
            ->get();

        foreach ($dbTeams as $team) {
            $trend = [];
            foreach ($gameweeks as $gw) {
                $points = PlayerPerformance::where('gameweek_id', $gw->id)
                    ->whereHas('player', fn($q) => $q->where('team_id', $team->id))
                    ->sum('total_points');
                $trend[] = [
                    'gameweek' => $gw->fpl_id,
                    'points' => $points,
                ];
            }

            $performances = $team->performances()->where('player_performances.gameweek_id', $latestGameweek->id)->orderByDesc('total_points')->get()->map(function ($performance) {
                return [
                    'id' => $performance->id,
                    'name' => $performance->player->web_name,
                    'position' => $performance->player->position,
                    'minutes' => $performance->minutes,
                    'goals_scored' => $performance->goals_scored,
                    'assists' => $performance->assists,
                    'clean_sheets' => $performance->clean_sheets,
                    'goals_conceded' => $performance->goals_conceded,
                    'own_goals' => $performance->own_goals,
                    'penalties_saved' => $performance->penalties_saved,
                    'penalties_missed' => $performance->penalties_missed,
                    'red_cards' => $performance->red_cards,
                    'yellow_cards' => $performance->yellow_cards,
                    'saves' => $performance->saves,
                    'total_points' => $performance->total_points,
                ];
            })->values();


            $filteredFixtures = $upcomingFixtures->filter(function ($fixture) use ($team) {
                return $fixture->team_h == $team->id || $fixture->team_a == $team->id;
            })->map(function ($fixture) {
                return [
                    'id' => $fixture->id,
                    'fpl_id' => $fixture->fpl_id,
                    'team_h' => $fixture->homeTeam->name,
                    'team_h_id' => $fixture->homeTeam->id,
                    'team_a' => $fixture->awayTeam->name,
                    'team_a_id' => $fixture->awayTeam->id,
                    'event' => $fixture->event,
                    'kickoff_date' => Carbon::parse($fixture->kickoff_time)->format('Y-m-d'),
                    'kickoff_time' => Carbon::parse($fixture->kickoff_time)->format('H:i'),
                ];
            })->values();

            $teams[] = [
                'id' => $team->id,
                'name' => $team->name,
                'points' => $team->total_points,
                'trend' => $trend,
                'players' => $performances,
                'upcomingFixtures' => $filteredFixtures
            ];
        }

        return $teams;
    }

    private function topTeamsAllWeeks()
    {
        $teams = Team::all();
        $gameweeks = Gameweek::orderBy('fpl_id')->get();

        $teamPointsTrend = [];

        foreach ($teams as $team) {
            $trend = [];
            foreach ($gameweeks as $gw) {
                $points = PlayerPerformance::where('gameweek_id', $gw->id)
                    ->whereHas('player', fn($q) => $q->where('team_id', $team->id))
                    ->sum('total_points');

                $trend[] = [
                    'gameweek' => $gw->fpl_id,
                    'points' => $points,
                ];
            }

            $teamPointsTrend[] = [
                'team' => $team->name,
                'trend' => $trend,
            ];
        }

        return $teamPointsTrend;
    }

    private function upcomingFixtures()
    {

        $nextGameweek = $this->getCurrentGameWeek()->id + 1;
        return Fixture::where('event', $nextGameweek)
            ->orderBy('kickoff_time');
    }

    private function getCurrentGameWeek()
    {
        return Gameweek::where('is_finished', true)
            ->orderByDesc('id')
            ->first();
    }
}
