<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\SalaryCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportPlayers extends Command
{
    protected $signature = 'import:players {file=players.csv}';

    protected $description = 'Import NBA players from CSV file';

    public function handle()
    {
        $filename = $this->argument('file');

        $possiblePaths = [
            storage_path('app/' . $filename),
            base_path($filename),
            base_path('storage/app/' . $filename),
        ];

        $filepath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filepath = $path;
                break;
            }
        }

        if (!$filepath) {
            $this->error("File not found. Tried locations:");
            foreach ($possiblePaths as $path) {
                $this->error("  - {$path}");
            }
            return 1;
        }

        $this->info("Importing players from {$filepath}...");

        $handle = fopen($filepath, 'r');

        $headers = fgetcsv($handle);

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $data = array_combine($headers, $row);

                if (empty($data['Player'])) {
                    continue;
                }

                $position = $this->normalizePosition($data['Pos'] ?? 'F');

                $team = substr($data['Team'] ?? 'UNK', 0, 3);

                $salary = SalaryCalculator::calculate([
                    'ppg' => (float) ($data['PTS/G_2'] ?? $data['PTS/G'] ?? 0),
                    'rpg' => (float) ($data['TRB'] ?? 0),
                    'apg' => (float) ($data['AST'] ?? 0),
                    'spg' => (float) ($data['STL'] ?? 0),
                    'bpg' => (float) ($data['BLK'] ?? 0),
                    'topg' => (float) ($data['TOV'] ?? 0),
                    'mpg' => (float) ($data['MP'] ?? 0),
                    'position' => $position,
                ]);

                Player::updateOrCreate(
                    [
                        'name' => $data['Player'],
                    ],
                    [
                        'team' => $team,
                        'position' => $position,
                        'ppg' => (float) ($data['PTS/G_2'] ?? $data['PTS/G'] ?? 0),
                        'rpg' => (float) ($data['TRB'] ?? 0),
                        'apg' => (float) ($data['AST'] ?? 0),
                        'spg' => (float) ($data['STL'] ?? 0),
                        'bpg' => (float) ($data['BLK'] ?? 0),
                        'topg' => (float) ($data['TOV'] ?? 0),
                        'mpg' => (float) ($data['MP'] ?? 0),
                        'salary' => $salary,
                        'is_playing' => true,
                    ]
                );

                $imported++;
                if ($imported % 10 == 0) {
                    $this->info("Imported {$imported} players...");
                }

            } catch (\Exception $e) {
                $skipped++;
                $this->warn("Skipped row: " . $e->getMessage());
            }
        }

        fclose($handle);

        $this->info("\n✓ Import complete!");
        $this->info("Imported: {$imported} players");
        if ($skipped > 0) {
            $this->warn("Skipped: {$skipped} rows");
        }

        return 0;
    }

    private function normalizePosition(string $pos): string
    {
        $posMap = [
            'G' => 'PG',
            'F' => 'SF',
            'C' => 'C',
            'G-F' => 'SG',
            'F-G' => 'SF',
            'F-C' => 'PF',
            'C-F' => 'C',
        ];

        return $posMap[$pos] ?? 'SF';
    }
}
