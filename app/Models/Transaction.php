<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'contest_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user this transaction belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contest this transaction is related to (if any)
     */
    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Boot method to set created_at automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->created_at) {
                $transaction->created_at = now();
            }
        });
    }
}
