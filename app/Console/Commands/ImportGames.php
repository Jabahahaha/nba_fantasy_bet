<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ImportGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:games {file=nba_calendar_cleaned.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import NBA game schedule from CSV file';

    /**
     * Execute the console command.
     */
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

        $this->info("Importing games from {$filepath}...");

        $handle = fopen($filepath, 'r');

        $headers = fgetcsv($handle);

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $data = array_combine($headers, $row);

                if (empty($data['Date'])) {
                    continue;
                }

                $gameDate = Carbon::parse($data['Date']);

                $startTime = $this->parseTime($data['Start (ET)'] ?? '7:00p');

                $visitorTeam = $this->getTeamAbbreviation($data['VisitorTeam'] ?? '');
                $homeTeam = $this->getTeamAbbreviation($data['HomeTeam'] ?? '');

                if (!$visitorTeam || !$homeTeam) {
                    $skipped++;
                    continue;
                }

                Game::updateOrCreate(
                    [
                        'game_date' => $gameDate->toDateString(),
                        'visitor_team' => $visitorTeam,
                        'home_team' => $homeTeam,
                    ],
                    [
                        'start_time' => $startTime,
                        'arena' => $data['Arena'] ?? null,
                        'notes' => $data['Notes'] ?? null,
                        'status' => 'scheduled',
                    ]
                );

                $imported++;
                if ($imported % 50 == 0) {
                    $this->info("Imported {$imported} games...");
                }

            } catch (\Exception $e) {
                $skipped++;
                $this->warn("Skipped row: " . $e->getMessage());
            }
        }

        fclose($handle);

        $this->info("\n✓ Import complete!");
        $this->info("Imported: {$imported} games");
        if ($skipped > 0) {
            $this->warn("Skipped: {$skipped} rows");
        }

        $uniqueDates = Game::distinct('game_date')->count('game_date');
        $this->info("\nSchedule covers {$uniqueDates} unique dates");

        return 0;
    }

    /**
     * Parse time from various formats (7:00p, 7:30p, 10:00p) to 24-hour format
     */
    private function parseTime(string $time): string
    {
        $time = str_replace(' ', '', $time);

        preg_match('/(\d+):(\d+)([ap])?/i', $time, $matches);

        if (count($matches) < 3) {
            return '19:00:00';
        }

        $hour = (int) $matches[1];
        $minute = (int) $matches[2];
        $period = strtolower($matches[3] ?? 'p');

        if ($period === 'p' && $hour < 12) {
            $hour += 12;
        } elseif ($period === 'a' && $hour === 12) {
            $hour = 0;
        }

        return sprintf('%02d:%02d:00', $hour, $minute);
    }

    /**
     * Get team abbreviation from full team name
     */
    private function getTeamAbbreviation(string $teamName): ?string
    {
        $teamMap = [
            'Atlanta Hawks' => 'ATL',
            'Boston Celtics' => 'BOS',
            'Brooklyn Nets' => 'BRK',
            'Charlotte Hornets' => 'CHO',
            'Chicago Bulls' => 'CHI',
            'Cleveland Cavaliers' => 'CLE',
            'Dallas Mavericks' => 'DAL',
            'Denver Nuggets' => 'DEN',
            'Detroit Pistons' => 'DET',
            'Golden State Warriors' => 'GSW',
            'Houston Rockets' => 'HOU',
            'Indiana Pacers' => 'IND',
            'Los Angeles Clippers' => 'LAC',
            'Los Angeles Lakers' => 'LAL',
            'Memphis Grizzlies' => 'MEM',
            'Miami Heat' => 'MIA',
            'Milwaukee Bucks' => 'MIL',
            'Minnesota Timberwolves' => 'MIN',
            'New Orleans Pelicans' => 'NOP',
            'New York Knicks' => 'NYK',
            'Oklahoma City Thunder' => 'OKC',
            'Orlando Magic' => 'ORL',
            'Philadelphia 76ers' => 'PHI',
            'Phoenix Suns' => 'PHO',
            'Portland Trail Blazers' => 'POR',
            'Sacramento Kings' => 'SAC',
            'San Antonio Spurs' => 'SAS',
            'Toronto Raptors' => 'TOR',
            'Utah Jazz' => 'UTA',
            'Washington Wizards' => 'WAS',
        ];

        return $teamMap[$teamName] ?? null;
    }
}
