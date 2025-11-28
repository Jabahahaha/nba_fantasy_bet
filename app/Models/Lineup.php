<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contest_id',
        'lineup_name',
        'total_salary_used',
        'fantasy_points_scored',
        'final_rank',
        'prize_won',
    ];

    protected $casts = [
        'fantasy_points_scored' => 'decimal:2',
    ];

    /**
     * Get the user who owns this lineup
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contest this lineup is in
     */
    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get all players in this lineup
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'lineup_players')
            ->withPivot([
                'position_slot',
                'simulated_points',
                'simulated_rebounds',
                'simulated_assists',
                'simulated_steals',
                'simulated_blocks',
                'simulated_turnovers',
                'fantasy_points_earned'
            ]);
    }

    /**
     * Get lineup players pivot records
     */
    public function lineupPlayers()
    {
        return $this->hasMany(LineupPlayer::class);
    }

    /**
     * Validate salary cap (must be <= $50,000)
     */
    public function validateSalaryCap(): bool
    {
        return $this->total_salary_used <= 50000;
    }

    /**
     * Validate position requirements
     * Must have: PG, SG, SF, PF, C, G (any guard), F (any forward), UTIL (any)
     */
    public function validatePositions(): bool
    {
        $players = $this->players;

        if ($players->count() !== 8) {
            return false;
        }

        $positions = $players->pluck('pivot.position_slot')->toArray();
        $requiredPositions = ['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL'];

        foreach ($requiredPositions as $required) {
            if (!in_array($required, $positions)) {
                return false;
            }
        }

        $gSlotPlayer = $players->firstWhere('pivot.position_slot', 'G');
        if ($gSlotPlayer && !in_array($gSlotPlayer->position, ['PG', 'SG'])) {
            return false;
        }

        $fSlotPlayer = $players->firstWhere('pivot.position_slot', 'F');
        if ($fSlotPlayer && !in_array($fSlotPlayer->position, ['SF', 'PF'])) {
            return false;
        }

        return true;
    }

    /**
     * Calculate total fantasy points for this lineup
     */
    public function calculateFantasyPoints(): float
    {
        $total = 0.0;

        foreach ($this->lineupPlayers as $lineupPlayer) {
            if ($lineupPlayer->fantasy_points_earned !== null) {
                $total += $lineupPlayer->fantasy_points_earned;
            }
        }

        $this->fantasy_points_scored = $total;
        $this->save();

        return $total;
    }
}
