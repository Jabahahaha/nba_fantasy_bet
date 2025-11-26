<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'points_balance',
        'total_contests_entered',
        'total_winnings',
    ];

    /**
     * The attributes with default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'points_balance' => 10000,
        'is_admin' => false,
        'total_contests_entered' => 0,
        'total_winnings' => 0,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get all lineups for this user
     */
    public function lineups()
    {
        return $this->hasMany(Lineup::class);
    }

    /**
     * Get all transactions for this user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the current balance
     */
    public function getCurrentBalance(): int
    {
        return $this->points_balance;
    }

    /**
     * Add points to user balance
     */
    public function addPoints(int $amount, string $type, string $description, ?int $contestId = null): void
    {
        $this->points_balance += $amount;
        $this->save();

        Transaction::create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $this->points_balance,
            'description' => $description,
            'contest_id' => $contestId,
        ]);
    }

    /**
     * Deduct points from user balance
     */
    public function deductPoints(int $amount, string $type, string $description, ?int $contestId = null): bool
    {
        if ($this->points_balance < $amount) {
            return false;
        }

        $this->points_balance -= $amount;
        $this->save();

        Transaction::create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => -$amount,
            'balance_after' => $this->points_balance,
            'description' => $description,
            'contest_id' => $contestId,
        ]);

        return true;
    }
}
