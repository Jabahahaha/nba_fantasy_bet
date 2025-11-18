<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContestPayout extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'contest_id',
        'position_min',
        'position_max',
        'payout_amount',
    ];

    /**
     * Get the contest this payout belongs to
     */
    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }
}
