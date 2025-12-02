<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use League\Csv\Reader;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adjust path to where you keep the CSV in your repo
        $filePath = database_path('seeders/data/premier_league_players.csv');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $this->command->info("Reading CSV file...");
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // first row = header

        $records = collect($csv->getRecords());
        $this->command->info("Loaded " . $records->count() . " rows from dataset.");

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
                $player->squad_number = is_numeric($number) ? (int) $number : null;
                $player->save();

                $updated++;
            }
        }

        $this->command->info("✅ Updated {$updated} player records successfully!");
    }
}
