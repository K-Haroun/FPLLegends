<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Services\FplService;
use Illuminate\Console\Command;

class SyncFplData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-fpl-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from the FPL API';

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
        try {
            $bootstrap = $this->fpl->bootstrap();
            $teams = $bootstrap['teams'] ?? [];

            foreach ($teams as $team) {
                Team::updateOrCreate(
                    ['fpl_id' => $team['id']],
                    [
                        'name' => $team['name'],
                        'short_name' => $team['short_name'],
                    ]
                );
            }

            $this->info('Synced '.count($teams).' teams.');

        } catch (\Throwable $e) {
            $this->error('Sync failed: '.$e->getMessage());
        }
    }
}
