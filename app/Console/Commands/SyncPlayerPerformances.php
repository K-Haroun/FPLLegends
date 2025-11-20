<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\Gameweek;
use App\Models\Player;
use App\Models\PlayerPerformance;
use App\Services\FplService;
use Illuminate\Console\Command;

class SyncPlayerPerformances extends Command
{
    protected $signature = 'app:sync-player-performances {--gameweek_id=}';

    protected $description = 'Sync player performance data from FPL API';

    protected FplService $fpl;

    public function __construct(FplService $fpl)
    {
        parent::__construct();
        $this->fpl = $fpl;
    }

    public function handle()
    {
        $gwId = $this->option('gameweek_id');

        if ($gwId) {
            $gameweeks = Gameweek::where('fpl_id', $gwId)->get();
        } else {
            $gameweeks = Gameweek::where('is_current', true)->get();
        }

        foreach ($gameweeks as $gameweek) {
            $this->syncGameweek($gameweek);
        }

        $this->info('✅ Player performances synced.');
    }

    protected function syncGameweek(Gameweek $gameweek)
    {
        $this->info("Fetching performances for GW {$gameweek->fpl_id}...");
        $data = $this->fpl->gameweekLive($gameweek->fpl_id);
        $elements = $data['elements'] ?? [];

        foreach ($elements as $element) {
            $player = Player::where('fpl_id', $element['id'])->first();

            if (!$player) {
                continue; // skip unknown players
            }

            // players can have multiple fixtures in a GW (e.g. double GW)
            foreach ($element['explain'] as $fixtureStats) {
                $fixture = Fixture::where('fpl_id', $fixtureStats['fixture'])->first();
                if (!$fixture) {
                    continue;
                }

                $stats = collect($element['stats']);

                PlayerPerformance::updateOrCreate(
                    [
                        'player_id' => $player->id,
                        'fixture_id' => $fixture->id,
                        'gameweek_id' => $gameweek->id,
                    ],
                    [
                        'minutes' => $stats['minutes'] ?? 0,
                        'goals_scored' => $stats['goals_scored'] ?? 0,
                        'assists' => $stats['assists'] ?? 0,
                        'clean_sheets' => $stats['clean_sheets'] ?? 0,
                        'goals_conceded' => $stats['goals_conceded'] ?? 0,
                        'own_goals' => $stats['own_goals'] ?? 0,
                        'penalties_saved' => $stats['penalties_saved'] ?? 0,
                        'penalties_missed' => $stats['penalties_missed'] ?? 0,
                        'yellow_cards' => $stats['yellow_cards'] ?? 0,
                        'red_cards' => $stats['red_cards'] ?? 0,
                        'saves' => $stats['saves'] ?? 0,
                        'bonus' => $stats['bonus'] ?? 0,
                        'bps' => $stats['bps'] ?? 0,
                        'influence' => $stats['influence'] ?? 0,
                        'creativity' => $stats['creativity'] ?? 0,
                        'threat' => $stats['threat'] ?? 0,
                        'ict_index' => $stats['ict_index'] ?? 0,
                        'clearances_blocks_interceptions' => $stats['clearances_blocks_interceptions'] ?? 0,
                        'recoveries' => $stats['recoveries'] ?? 0,
                        'tackles' => $stats['tackles'] ?? 0,
                        'defensive_contribution' => $stats['defensive_contribution'] ?? 0,
                        'starts' => $stats['starts'] ?? 0,
                        'expected_goals' => $stats['expected_goals'] ?? 0,
                        'expected_assists' => $stats['expected_assists'] ?? 0,
                        'expected_goal_involvements' => $stats['expected_goal_involvements'] ?? 0,
                        'expected_goals_conceded' => $stats['expected_goals_conceded'] ?? 0,
                        'total_points' => $stats['total_points'] ?? 0,
                    ]
                );
            }
        }
    }
}
