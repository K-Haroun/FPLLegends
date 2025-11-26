<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportPlayerBiosFromCsv extends Command
{
    protected $signature = 'app:import-player-bios {file=storage/app/data/premier_league_players.csv}';
    protected $description = 'Import player nationality and squad number from CSV dataset';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return;
        }

        $this->info("Reading CSV file...");
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // first row = header

        $records = collect($csv->getRecords());
        $this->info("Loaded " . $records->count() . " rows from dataset.");

        $updated = 0;

        foreach ($records as $row) {
            $name = trim($row['Player'] ?? '');
            $nation = trim($row['Nation'] ?? '');
            $number = trim($row['#'] ?? '');

            if (!$name) {
                continue;
            }

            // Try to match by web_name or partial name
            $player = Player::where('web_name', 'like', "%{$name}%")
                ->orWhere(function ($q) use ($name) {
                    $q->whereRaw("CONCAT(first_name, ' ', second_name) LIKE ?", ["%{$name}%"]);
                })
                ->first();

            if ($player) {
                $player->nationality = $nation;
                $player->squad_number = is_numeric($number) ? (int)$number : null;
                $player->save();

                $updated++;
            }
        }

        $this->info("✅ Updated {$updated} player records successfully!");
    }
}
