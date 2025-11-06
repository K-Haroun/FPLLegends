<?php

namespace App\Console\Commands;

use App\Models\Gameweek;
use App\Services\FplService;
use Illuminate\Console\Command;

class SyncGameweeksData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-gameweeks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info('Fetching gameweeks...');

        $data = $this->fpl->bootstrap();
        $gameweeks = $data['events'] ?? [];

        $this->info('Found '.count($gameweeks).' gameweeks.');

        foreach ($gameweeks as $gw) {
            Gameweek::updateOrCreate(
                ['fpl_id' => $gw['id']],
                [
                    'name' => $gw['name'] ?? null,
                    'kickoff_time' => isset($gw['kickoff_time'])
                            ? date('Y-m-d H:i:s', strtotime($gw['kickoff_time']))
                            : null,
                    'is_current' => $gw['is_current'] ?? false,
                    'is_next' => $gw['is_next'] ?? false,
                    'is_finished' => $gw['finished'] ?? false,
                ]
            );
        }

        $this->info('Gameweeks synced successfully.');
    }
}
