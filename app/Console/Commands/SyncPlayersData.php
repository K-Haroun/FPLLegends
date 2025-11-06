<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\FplService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class SyncPlayersData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync player data from the FPL API into the database';

    protected FplService $fpl;

    public function __construct(FplService $fpl)
    {
        parent::__construct();
        $this->fpl = $fpl;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching player data...');

        $data = $this->fpl->bootstrap();

        if (! isset($data['elements']) || ! is_array($data['elements'])) {
            $this->error('Failed to fetch player data: "elements" key not found.');

            return;
        }

        $players = $data['elements'];

        $this->info('Found '.count($players).' players.');

        foreach ($players as $playerData) {
            try {
                Player::updateOrCreate(
                    ['fpl_id' => $playerData['id']],
                    [
                        'web_name' => $playerData['web_name'],
                        'first_name' => $playerData['first_name'],
                        'second_name' => $playerData['second_name'],
                        'team_id' => $playerData['team'],
                        'position' => $playerData['element_type'],
                        'now_cost' => $playerData['now_cost'],
                        'birth_date' => $playerData['birth_date'],
                        'news' => $playerData['news'],
                        'photo' => $playerData['photo'],
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Failed to sync player', [
                    'player_id' => $playerData['id'] ?? null,
                    'message' => $e->getMessage(),
                ]);

                $this->warn("⚠️ Skipped player ID {$playerData['id']} due to error.");
            }
        }

        $this->info('All players synced successfully ✅');
    }
}
