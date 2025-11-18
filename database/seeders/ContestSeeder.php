<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ContestSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get games from the database
        $games = Game::orderBy('game_date', 'asc')->take(10)->get();

        if ($games->isEmpty()) {
            $this->command->error('No games found in database. Please import games first.');
            return;
        }

        $this->command->info('Creating sample contests...');

        // Group games by date
        $gamesByDate = $games->groupBy('game_date');

        $contestNumber = 1;

        foreach ($gamesByDate as $date => $dateGames) {
            $gameDate = Carbon::parse($date);
            $lockTime = $gameDate->copy()->subHours(1); // Contest locks 1 hour before games

            // Create a 50/50 Contest
            Contest::create([
                'name' => "NBA 50/50 Contest #{$contestNumber} - " . $gameDate->format('M d'),
                'contest_type' => '50-50',
                'contest_date' => $gameDate->toDateString(),
                'entry_fee' => 100,
                'prize_pool' => 2000,
                'max_entries' => 100,
                'lock_time' => $lockTime,
                'status' => $lockTime->isFuture() ? 'upcoming' : 'live',
            ]);

            $contestNumber++;

            // Create a GPP Contest
            Contest::create([
                'name' => "NBA GPP Tournament #{$contestNumber} - " . $gameDate->format('M d'),
                'contest_type' => 'GPP',
                'contest_date' => $gameDate->toDateString(),
                'entry_fee' => 50,
                'prize_pool' => 5000,
                'max_entries' => 500,
                'lock_time' => $lockTime,
                'status' => $lockTime->isFuture() ? 'upcoming' : 'live',
            ]);

            $contestNumber++;

            // Create a Head-to-Head Contest
            Contest::create([
                'name' => "NBA H2H Challenge #{$contestNumber} - " . $gameDate->format('M d'),
                'contest_type' => 'H2H',
                'contest_date' => $gameDate->toDateString(),
                'entry_fee' => 200,
                'prize_pool' => 360, // 90% of 2 * 200
                'max_entries' => 2,
                'lock_time' => $lockTime,
                'status' => $lockTime->isFuture() ? 'upcoming' : 'live',
            ]);

            $contestNumber++;

            // Create one more GPP with higher entry for variety
            if ($contestNumber % 4 == 0) {
                Contest::create([
                    'name' => "NBA High Roller GPP #{$contestNumber} - " . $gameDate->format('M d'),
                    'contest_type' => 'GPP',
                    'contest_date' => $gameDate->toDateString(),
                    'entry_fee' => 500,
                    'prize_pool' => 50000,
                    'max_entries' => 200,
                    'lock_time' => $lockTime,
                    'status' => $lockTime->isFuture() ? 'upcoming' : 'live',
                ]);

                $contestNumber++;
            }
        }

        $this->command->info("✅ Created {$contestNumber} sample contests.");
        $this->command->info('   Contest types: 50/50, GPP, H2H');
        $this->command->info('   Based on available game dates in the database');
    }
}
