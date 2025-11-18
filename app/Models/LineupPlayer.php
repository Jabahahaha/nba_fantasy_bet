<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineupPlayer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'lineup_id',
        'player_id',
        'position_slot',
        'simulated_points',
        'simulated_rebounds',
        'simulated_assists',
        'simulated_steals',
        'simulated_blocks',
        'simulated_turnovers',
        'fantasy_points_earned',
    ];

    protected $casts = [
        'fantasy_points_earned' => 'decimal:2',
    ];

    /**
     * Get the lineup this belongs to
     */
    public function lineup()
    {
        return $this->belongsTo(Lineup::class);
    }

    /**
     * Get the player
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
