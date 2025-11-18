<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayerStat extends Model
{
    protected $fillable = [
        'game_id',
        'player_id',
        'points',
        'rebounds',
        'assists',
        'steals',
        'blocks',
        'turnovers',
        'fantasy_points',
    ];

    /**
     * Get the game for this stat
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the player for this stat
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
