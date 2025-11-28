<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Game extends Model
{
    protected $fillable = [
        'game_date',
        'start_time',
        'visitor_team',
        'home_team',
        'visitor_score',
        'home_score',
        'arena',
        'notes',
        'status',
        'winner',
        'simulated_at',
    ];

    protected $casts = [
        'game_date' => 'date',
        'simulated_at' => 'datetime',
    ];

    /**
     * Get all player stats for this game
     */
    public function playerStats()
    {
        return $this->hasMany(GamePlayerStat::class)->with('player');
    }

    /**
     * Get all players playing in this game
     */
    public function players()
    {
        return Player::whereIn('team', [$this->visitor_team, $this->home_team])
            ->where('is_playing', true)
            ->get();
    }

    /**
     * Get games for a specific date
     */
    public static function getGamesForDate($date)
    {
        return self::whereRaw('DATE(game_date) = ?', [Carbon::parse($date)->toDateString()])
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get all teams playing on a specific date
     */
    public static function getTeamsPlayingOnDate($date)
    {
        $games = self::getGamesForDate($date);
        $teams = [];

        foreach ($games as $game) {
            $teams[] = $game->visitor_team;
            $teams[] = $game->home_team;
        }

        return array_unique($teams);
    }

    /**
     * Check if a team is playing on a specific date
     */
    public static function isTeamPlaying($team, $date)
    {
        return self::whereRaw('DATE(game_date) = ?', [Carbon::parse($date)->toDateString()])
            ->where(function($query) use ($team) {
                $query->where('visitor_team', $team)
                      ->orWhere('home_team', $team);
            })
            ->exists();
    }

    /**
     * Get the game for a specific matchup on a date
     */
    public static function getGameForTeam($team, $date)
    {
        return self::whereRaw('DATE(game_date) = ?', [Carbon::parse($date)->toDateString()])
            ->where(function($query) use ($team) {
                $query->where('visitor_team', $team)
                      ->orWhere('home_team', $team);
            })
            ->first();
    }

    /**
     * Calculate and set scores based on player performances
     */
    public function calculateScores(array $playerStats): void
    {
        $visitorScore = 0;
        $homeScore = 0;

        foreach ($playerStats as $playerId => $stats) {
            if ($stats['team'] === $this->visitor_team) {
                $visitorScore += $stats['points'];
            } elseif ($stats['team'] === $this->home_team) {
                $homeScore += $stats['points'];
            }

            GamePlayerStat::updateOrCreate(
                [
                    'game_id' => $this->id,
                    'player_id' => $playerId,
                ],
                [
                    'points' => $stats['points'],
                    'rebounds' => $stats['rebounds'],
                    'assists' => $stats['assists'],
                    'steals' => $stats['steals'],
                    'blocks' => $stats['blocks'],
                    'turnovers' => $stats['turnovers'],
                    'fantasy_points' => $stats['fantasy_points'],
                ]
            );
        }

        $this->visitor_score = $visitorScore;
        $this->home_score = $homeScore;
        $this->winner = $visitorScore > $homeScore ? $this->visitor_team : $this->home_team;
        $this->status = 'completed';
        $this->simulated_at = now();
        $this->save();
    }

    /**
     * Get formatted score string
     */
    public function getScoreString(): string
    {
        if ($this->visitor_score === null || $this->home_score === null) {
            return 'Not yet played';
        }

        return "{$this->visitor_team} {$this->visitor_score} - {$this->home_score} {$this->home_team}";
    }

    /**
     * Check if this game has been simulated
     */
    public function isSimulated(): bool
    {
        return $this->simulated_at !== null;
    }

    /**
     * Reset simulation data for this game
     */
    public function resetSimulation(): void
    {
        $this->playerStats()->delete();

        $this->visitor_score = null;
        $this->home_score = null;
        $this->winner = null;
        $this->status = 'scheduled';
        $this->simulated_at = null;
        $this->save();
    }
}
