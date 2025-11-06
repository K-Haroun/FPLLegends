<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\SportMonksService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncPlayerBios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-player-bios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync player bios (nationality, height, squad number) from SportMonks API';

    protected SportMonksService $sportMonks;

    public function __construct(SportMonksService $sportMonks)
    {
        parent::__construct();
        $this->sportMonks = $sportMonks;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching player bio data from SportMonks...');

        $players = Player::all();

        if ($players->isEmpty()) {
            $this->warn('No players found in database.');
            return;
        }

        $this->info("Found {$players->count()} players to update.");

        foreach ($players as $player) {
            try {
                $data = $this->sportMonks->getPlayerBio($player->web_name, $player->first_name, $player->second_name);

                if (empty($data)) {
                    $this->warn("No data found for {$player->web_name}");
                    continue;
                }

                $player->update([
                    'nationality' => $data['nationality'] ?? $player->nationality,
                    'height' => $data['height'] ?? $player->height,
                    'squad_number' => $data['squad_number'] ?? $player->squad_number,
                ]);

                $this->line("✅ Updated {$player->web_name}");
            } catch (\Exception $e) {
                Log::error('Failed to sync player bio', [
                    'player_id' => $player->id,
                    'message' => $e->getMessage(),
                ]);

                $this->warn("⚠️ Skipped {$player->web_name} due to error.");
            }

            // Small delay to avoid rate limits
            usleep(200000); // 0.2 seconds
        }

        $this->info('All player bios synced successfully ✅');
    }
}
