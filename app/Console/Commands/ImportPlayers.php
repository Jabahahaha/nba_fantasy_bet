<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\SalaryCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:players {file=players.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NBA players from CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->argument('file');

        // Try multiple locations
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

        // Read header row
        $headers = fgetcsv($handle);

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            try {
                // Map CSV columns to array
                $data = array_combine($headers, $row);

                // Skip if no player name
                if (empty($data['Player'])) {
                    continue;
                }

                // Map position from CSV format (G, F, C, G-F, F-C, etc) to our format
                $position = $this->normalizePosition($data['Pos'] ?? 'F');

                // Extract team (handle multi-team like "DALLAL" -> "DAL")
                $team = substr($data['Team'] ?? 'UNK', 0, 3);

                // Calculate salary
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

                // Create or update player
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

    /**
     * Normalize position from various formats to our standard (PG/SG/SF/PF/C)
     */
    private function normalizePosition(string $pos): string
    {
        // Map complex positions to primary position
        $posMap = [
            'G' => 'PG',      // Guard -> Point Guard
            'F' => 'SF',      // Forward -> Small Forward
            'C' => 'C',       // Center
            'G-F' => 'SG',    // Guard-Forward -> Shooting Guard
            'F-G' => 'SF',    // Forward-Guard -> Small Forward
            'F-C' => 'PF',    // Forward-Center -> Power Forward
            'C-F' => 'C',     // Center-Forward -> Center
        ];

        return $posMap[$pos] ?? 'SF'; // Default to SF if unknown
    }
}
