<?php

namespace App\Services;

use App\Models\Contest;
use App\Models\Player;
use App\Models\LineupPlayer;
use App\Models\Game;
use App\Models\GamePlayerStat;

class GameSimulator
{
    /**
     * Simulate player performance based on averages
     * Uses bell curve distribution for realistic variance
     */
    public function simulatePlayerPerformance(Player $player): array
    {
        // Generate performance multiplier using bell curve
        $multiplier = $this->generatePerformanceMultiplier();

        // Simulate each stat with the multiplier
        $points = $this->simulateStat($player->ppg, $multiplier, 0, 50);
        $rebounds = $this->simulateStat($player->rpg, $multiplier, 0, 20);
        $assists = $this->simulateStat($player->apg, $multiplier, 0, 20);
        $steals = $this->simulateStat($player->spg, $multiplier, 0, 5);
        $blocks = $this->simulateStat($player->bpg, $multiplier, 0, 5);
        $turnovers = $this->simulateStat($player->topg, $multiplier, 0, 8);

        return [
            'points' => $points,
            'rebounds' => $rebounds,
            'assists' => $assists,
            'steals' => $steals,
            'blocks' => $blocks,
            'turnovers' => $turnovers,
        ];
    }

    /**
     * Generate performance multiplier based on bell curve distribution
     * 10% bad (0.6-0.8x), 20% below avg (0.8-0.95x), 40% avg (0.95-1.05x),
     * 20% good (1.05-1.3x), 10% great (1.3-1.6x)
     */
    private function generatePerformanceMultiplier(): float
    {
        $rand = mt_rand(1, 100);

        if ($rand <= 10) {
            // Bad game: 10%
            return mt_rand(60, 80) / 100;
        } elseif ($rand <= 30) {
            // Below average: 20%
            return mt_rand(80, 95) / 100;
        } elseif ($rand <= 70) {
            // Average: 40%
            return mt_rand(95, 105) / 100;
        } elseif ($rand <= 90) {
            // Good game: 20%
            return mt_rand(105, 130) / 100;
        } else {
            // Great game: 10%
            return mt_rand(130, 160) / 100;
        }
    }

    /**
     * Simulate a stat with variance and bounds
     */
    private function simulateStat(float $average, float $multiplier, int $min, int $max): int
    {
        // Apply multiplier
        $value = $average * $multiplier;

        // Add small random variance (-10% to +10%)
        $variance = mt_rand(-10, 10) / 100;
        $value = $value * (1 + $variance);

        // Ensure within bounds
        $value = max($min, min($max, $value));

        return (int) round($value);
    }

    /**
     * Calculate fantasy points from stats
     * Formula: points*1 + rebounds*1.25 + assists*1.5 + steals*2 + blocks*2 - turnovers*0.5
     * Bonuses: +1.5 for double-double, +3 for triple-double
     */
    public function calculateFantasyPoints(array $stats): float
    {
        $points = $stats['points'] * 1.0;
        $rebounds = $stats['rebounds'] * 1.25;
        $assists = $stats['assists'] * 1.5;
        $steals = $stats['steals'] * 2.0;
        $blocks = $stats['blocks'] * 2.0;
        $turnovers = $stats['turnovers'] * 0.5;

        $total = $points + $rebounds + $assists + $steals + $blocks - $turnovers;

        // Check for double-double (2 stats >= 10)
        $doubleDigitStats = 0;
        if ($stats['points'] >= 10) $doubleDigitStats++;
        if ($stats['rebounds'] >= 10) $doubleDigitStats++;
        if ($stats['assists'] >= 10) $doubleDigitStats++;
        if ($stats['steals'] >= 10) $doubleDigitStats++;
        if ($stats['blocks'] >= 10) $doubleDigitStats++;

        if ($doubleDigitStats >= 3) {
            // Triple-double
            $total += 3.0;
        } elseif ($doubleDigitStats >= 2) {
            // Double-double
            $total += 1.5;
        }

        return round($total, 2);
    }

    /**
     * Simulate an entire contest
     * Now uses existing game results instead of simulating new games
     */
    public function simulateContest(Contest $contest): void
    {
        // First, ensure all games for the contest date are simulated
        $games = Game::getGamesForDate($contest->contest_date);
        foreach ($games as $game) {
            if (!$game->isSimulated()) {
                $this->simulateGame($game);
            }
        }

        $lineups = $contest->lineups()->with(['lineupPlayers.player'])->get();

        // Get teams playing on contest date
        $teamsPlaying = Game::getTeamsPlayingOnDate($contest->contest_date);

        // Simulate each lineup using existing game results
        foreach ($lineups as $lineup) {
            foreach ($lineup->lineupPlayers as $lineupPlayer) {
                $player = $lineupPlayer->player;

                // Check if player's team is playing today
                $isPlaying = in_array($player->team, $teamsPlaying);

                if (!$isPlaying) {
                    // Player's team not playing - score 0 points
                    $lineupPlayer->simulated_points = 0;
                    $lineupPlayer->simulated_rebounds = 0;
                    $lineupPlayer->simulated_assists = 0;
                    $lineupPlayer->simulated_steals = 0;
                    $lineupPlayer->simulated_blocks = 0;
                    $lineupPlayer->simulated_turnovers = 0;
                    $lineupPlayer->fantasy_points_earned = 0;
                    $lineupPlayer->save();
                    continue;
                }

                // Get player's stats from the game that was already simulated
                $game = Game::getGameForTeam($player->team, $contest->contest_date);

                if (!$game || !$game->isSimulated()) {
                    // Game not found or not simulated - shouldn't happen, but handle gracefully
                    $lineupPlayer->simulated_points = 0;
                    $lineupPlayer->simulated_rebounds = 0;
                    $lineupPlayer->simulated_assists = 0;
                    $lineupPlayer->simulated_steals = 0;
                    $lineupPlayer->simulated_blocks = 0;
                    $lineupPlayer->simulated_turnovers = 0;
                    $lineupPlayer->fantasy_points_earned = 0;
                    $lineupPlayer->save();
                    continue;
                }

                // Get player's stats from the game
                $playerStat = GamePlayerStat::where('game_id', $game->id)
                    ->where('player_id', $player->id)
                    ->first();

                if (!$playerStat) {
                    // Player didn't play in this game (not in top 10) - score 0
                    $lineupPlayer->simulated_points = 0;
                    $lineupPlayer->simulated_rebounds = 0;
                    $lineupPlayer->simulated_assists = 0;
                    $lineupPlayer->simulated_steals = 0;
                    $lineupPlayer->simulated_blocks = 0;
                    $lineupPlayer->simulated_turnovers = 0;
                    $lineupPlayer->fantasy_points_earned = 0;
                    $lineupPlayer->save();
                    continue;
                }

                // Use existing stats from the game
                $lineupPlayer->simulated_points = $playerStat->points;
                $lineupPlayer->simulated_rebounds = $playerStat->rebounds;
                $lineupPlayer->simulated_assists = $playerStat->assists;
                $lineupPlayer->simulated_steals = $playerStat->steals;
                $lineupPlayer->simulated_blocks = $playerStat->blocks;
                $lineupPlayer->simulated_turnovers = $playerStat->turnovers;
                $lineupPlayer->fantasy_points_earned = $playerStat->fantasy_points;
                $lineupPlayer->save();
            }

            // Calculate total fantasy points for lineup
            $lineup->calculateFantasyPoints();
        }

        // Rank lineups by fantasy points
        $rankedLineups = $contest->lineups()
            ->orderBy('fantasy_points_scored', 'desc')
            ->get();

        $rank = 1;
        foreach ($rankedLineups as $lineup) {
            $lineup->final_rank = $rank;
            $lineup->save();
            $rank++;
        }

        // Distribute prizes
        $contest->distributePrizes();
    }

    /**
     * Check if a player is playing on a given date
     */
    public function isPlayerPlaying(Player $player, $date): bool
    {
        return Game::isTeamPlaying($player->team, $date);
    }

    /**
     * Simulate a single game independently
     * This is the centralized game simulation method that all contests will use
     */
    public function simulateGame(Game $game): void
    {
        // Don't simulate if already simulated
        if ($game->isSimulated()) {
            return;
        }

        // Get top 10 players by minutes per game for both teams
        $visitorPlayers = Player::where('team', $game->visitor_team)
            ->where('is_playing', true)
            ->orderByDesc('mpg')
            ->limit(10)
            ->get();

        $homePlayers = Player::where('team', $game->home_team)
            ->where('is_playing', true)
            ->orderByDesc('mpg')
            ->limit(10)
            ->get();

        $allPlayers = $visitorPlayers->merge($homePlayers);

        // Simulate performance for each player
        $playerPerformances = [];
        foreach ($allPlayers as $player) {
            $stats = $this->simulatePlayerPerformance($player);
            $fantasyPoints = $this->calculateFantasyPoints($stats);

            $playerPerformances[$player->id] = [
                'team' => $player->team,
                'points' => $stats['points'],
                'rebounds' => $stats['rebounds'],
                'assists' => $stats['assists'],
                'steals' => $stats['steals'],
                'blocks' => $stats['blocks'],
                'turnovers' => $stats['turnovers'],
                'fantasy_points' => $fantasyPoints,
            ];
        }

        // Calculate and save game scores
        $game->calculateScores($playerPerformances);
    }
}
