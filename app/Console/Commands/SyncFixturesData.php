<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Services\FplService;
use Illuminate\Console\Command;

class SyncFixturesData extends Command
{
    protected $signature = 'app:sync-fixtures';

    protected $description = 'Fetch and store fixtures from FPL API';

    protected FplService $fpl;

    public function __construct(FplService $fpl)
    {
        parent::__construct();
        $this->fpl = $fpl;
    }

    public function handle()
    {
        $this->info('Fetching fixtures...');

        try {
            $fixtures = $this->fpl->fixtures();

            foreach ($fixtures as $fixture) {
                Fixture::updateOrCreate(
                    ['fpl_id' => $fixture['id']],
                    [
                        'team_h' => $fixture['team_h'] ?? null,
                        'team_a' => $fixture['team_a'] ?? null,
                        'event' => $fixture['event'] ?? null,
                        'kickoff_time' => isset($fixture['kickoff_time'])
                            ? date('Y-m-d H:i:s', strtotime($fixture['kickoff_time']))
                            : null,
                        'team_h_score' => $fixture['team_h_score'] ?? null,
                        'team_a_score' => $fixture['team_a_score'] ?? null,
                        'finished' => $fixture['finished'] ?? false,
                    ]
                );
            }

            $this->info('Fixtures fetched and stored successfully.');
        } catch (\Exception $e) {
            $this->error('Error fetching fixtures: '.$e->getMessage());
        }

        return Command::SUCCESS;
    }
}
