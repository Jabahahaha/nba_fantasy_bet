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
        $isLowVolumeScorer = $player->ppg < 20;

        $multiplier = $this->generatePerformanceMultiplierForPlayer($player);

        $points = $this->simulateStat($player->ppg, $multiplier, 0, 50, $isLowVolumeScorer);
        $rebounds = $this->simulateStat($player->rpg, $multiplier, 0, 20, $isLowVolumeScorer);
        $assists = $this->simulateStat($player->apg, $multiplier, 0, 20, $isLowVolumeScorer);
        $steals = $this->simulateStat($player->spg, $multiplier, 0, 5, $isLowVolumeScorer);
        $blocks = $this->simulateStat($player->bpg, $multiplier, 0, 5, $isLowVolumeScorer);
        $turnovers = $this->simulateStat($player->topg, $multiplier, 0, 8, $isLowVolumeScorer);

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
     * Creates a performance multiplier that biases low-volume scorers to underperform slightly more often.
     */
    private function generatePerformanceMultiplierForPlayer(Player $player): float
    {
        if ($player->ppg >= 20) {
            return $this->generatePerformanceMultiplier();
        }

        $rand = mt_rand(1, 100);

        if ($rand <= 25) {
            // Heavy underperformance: 25%
            return mt_rand(45, 75) / 100;
        } elseif ($rand <= 60) {
            // Below average but still volatile: 35%
            return mt_rand(75, 95) / 100;
        } elseif ($rand <= 85) {
            // Average outing: 25%
            return mt_rand(95, 110) / 100;
        } elseif ($rand <= 95) {
            // Good night still possible: 10%
            return mt_rand(110, 130) / 100;
        }

        // Great games are rare but possible: 5%
        return mt_rand(130, 150) / 100;
    }

    /**
     * Simulate a stat with variance and bounds
     */
    private function simulateStat(float $average, float $multiplier, int $min, int $max, bool $isLowVolumeScorer = false): int
    {
        $value = $average * $multiplier;

        if ($isLowVolumeScorer) {
            $variance = mt_rand(-25, 12) / 100;
        } else {
            $variance = mt_rand(-10, 10) / 100;
        }
        $value = $value * (1 + $variance);

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

        $doubleDigitStats = 0;
        if ($stats['points'] >= 10) $doubleDigitStats++;
        if ($stats['rebounds'] >= 10) $doubleDigitStats++;
        if ($stats['assists'] >= 10) $doubleDigitStats++;
        if ($stats['steals'] >= 10) $doubleDigitStats++;
        if ($stats['blocks'] >= 10) $doubleDigitStats++;

        if ($doubleDigitStats >= 3) {
            $total += 3.0;
        } elseif ($doubleDigitStats >= 2) {
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
        $games = Game::getGamesForDate($contest->contest_date);
        foreach ($games as $game) {
            if (!$game->isSimulated()) {
                $this->simulateGame($game);
            }
        }

        $lineups = $contest->lineups()->with(['lineupPlayers.player'])->get();

        $teamsPlaying = Game::getTeamsPlayingOnDate($contest->contest_date);

        foreach ($lineups as $lineup) {
            foreach ($lineup->lineupPlayers as $lineupPlayer) {
                $player = $lineupPlayer->player;

                $isPlaying = in_array($player->team, $teamsPlaying);

                if (!$isPlaying) {
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

                $game = Game::getGameForTeam($player->team, $contest->contest_date);

                if (!$game || !$game->isSimulated()) {
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

                $playerStat = GamePlayerStat::where('game_id', $game->id)
                    ->where('player_id', $player->id)
                    ->first();

                if (!$playerStat) {
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

                $lineupPlayer->simulated_points = $playerStat->points;
                $lineupPlayer->simulated_rebounds = $playerStat->rebounds;
                $lineupPlayer->simulated_assists = $playerStat->assists;
                $lineupPlayer->simulated_steals = $playerStat->steals;
                $lineupPlayer->simulated_blocks = $playerStat->blocks;
                $lineupPlayer->simulated_turnovers = $playerStat->turnovers;
                $lineupPlayer->fantasy_points_earned = $playerStat->fantasy_points;
                $lineupPlayer->save();
            }

            $lineup->calculateFantasyPoints();
        }

        $rankedLineups = $contest->lineups()
            ->orderBy('fantasy_points_scored', 'desc')
            ->get();

        $rank = 1;
        foreach ($rankedLineups as $lineup) {
            $lineup->final_rank = $rank;
            $lineup->save();
            $rank++;
        }

        $contest->calculatePrizes();

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
        if ($game->isSimulated()) {
            return;
        }

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

        $game->calculateScores($playerPerformances);
    }
}
