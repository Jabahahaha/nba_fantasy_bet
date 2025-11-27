<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'entry_fee',
        'max_entries',
        'max_entries_per_user',
        'current_entries',
        'prize_pool',
        'contest_date',
        'lock_time',
        'status',
        'contest_type',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'contest_date' => 'date',
        'lock_time' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get all lineups in this contest
     */
    public function lineups()
    {
        return $this->hasMany(Lineup::class);
    }

    /**
     * Get payout structure for this contest
     */
    public function payouts()
    {
        return $this->hasMany(ContestPayout::class);
    }

    /**
     * Check if contest is open for entries
     */
    public function isOpen(): bool
    {
        return $this->status === 'upcoming' &&
               $this->lock_time->isFuture() &&
               $this->current_entries < $this->max_entries;
    }

    /**
     * Check if contest is locked
     */
    public function isLocked(): bool
    {
        return $this->lock_time->isPast() || $this->status !== 'upcoming';
    }

    /**
     * Get seconds until lock time
     */
    public function getSecondsUntilLock(): int
    {
        if ($this->lock_time->isPast()) {
            return 0;
        }
        return $this->lock_time->diffInSeconds(now());
    }

    /**
     * Lock the contest
     */
    public function lock(): void
    {
        $this->status = 'live';
        $this->save();
    }

    /**
     * Calculate prizes based on prize pool and contest type
     */
    public function calculatePrizes(): void
    {
        // Clear existing payouts
        $this->payouts()->delete();

        $prizePool = $this->prize_pool;
        $totalEntries = $this->current_entries;

        if ($totalEntries == 0) {
            return;
        }

        switch ($this->contest_type) {
            case '50-50':
                // Top 50% get double their entry
                $winners = (int) ceil($totalEntries / 2);
                $payoutPerWinner = (int) floor($prizePool / $winners);

                ContestPayout::create([
                    'contest_id' => $this->id,
                    'position_min' => 1,
                    'position_max' => $winners,
                    'payout_amount' => $payoutPerWinner,
                ]);
                break;

            case 'H2H':
                // Head to head - winner takes all
                ContestPayout::create([
                    'contest_id' => $this->id,
                    'position_min' => 1,
                    'position_max' => 1,
                    'payout_amount' => $prizePool,
                ]);
                break;

            case 'GPP':
                // Guaranteed Prize Pool - top-heavy distribution
                // 1st: 20%, 2nd: 10%, 3rd: 7%, 4th-5th: 5%, 6th-10th: 3%, 11th-20th: 1.5%, 21st-30th: 1%
                $payouts = [
                    [1, 1, 0.20],      // 1st place
                    [2, 2, 0.10],      // 2nd place
                    [3, 3, 0.07],      // 3rd place
                    [4, 5, 0.05],      // 4th-5th
                    [6, 10, 0.03],     // 6th-10th
                    [11, 20, 0.015],   // 11th-20th
                    [21, 30, 0.01],    // 21st-30th
                ];

                foreach ($payouts as [$min, $max, $percentage]) {
                    if ($min <= $totalEntries) {
                        $actualMax = min($max, $totalEntries);
                        $spots = $actualMax - $min + 1;
                        $totalForRange = $prizePool * $percentage;
                        $payoutPerSpot = (int) floor($totalForRange / $spots);

                        if ($payoutPerSpot > 0) {
                            ContestPayout::create([
                                'contest_id' => $this->id,
                                'position_min' => $min,
                                'position_max' => $actualMax,
                                'payout_amount' => $payoutPerSpot,
                            ]);
                        }
                    }
                }
                break;
        }
    }

    /**
     * Distribute prizes to winning lineups
     */
    public function distributePrizes(): void
    {
        $payouts = $this->payouts;
        $lineups = $this->lineups()
            ->whereNotNull('final_rank')
            ->orderBy('final_rank')
            ->get();

        foreach ($lineups as $lineup) {
            $rank = $lineup->final_rank;

            // Find applicable payout
            $payout = $payouts->first(function ($p) use ($rank) {
                return $rank >= $p->position_min && $rank <= $p->position_max;
            });

            if ($payout) {
                $prizeAmount = $payout->payout_amount;
                $lineup->prize_won = $prizeAmount;
                $lineup->save();

                // Add points to user
                $lineup->user->addPoints(
                    $prizeAmount,
                    'contest_won',
                    "Won {$prizeAmount} points in {$this->name} (Rank: {$rank})",
                    $this->id
                );

                // Update user stats
                $lineup->user->total_winnings += $prizeAmount;
                $lineup->user->save();
            }
        }

        // Mark contest as completed
        $this->status = 'completed';
        $this->save();
    }

    /**
     * Get the number of entries a user has in this contest
     */
    public function getUserEntryCount($userId): int
    {
        return $this->lineups()->where('user_id', $userId)->count();
    }

    /**
     * Check if user can enter more lineups in this contest
     */
    public function canUserEnter($userId): bool
    {
        return $this->getUserEntryCount($userId) < $this->max_entries_per_user;
    }

    /**
     * Get remaining entries for a user
     */
    public function getUserRemainingEntries($userId): int
    {
        return max(0, $this->max_entries_per_user - $this->getUserEntryCount($userId));
    }

    /**
     * Check if contest is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled' || $this->cancelled_at !== null;
    }

    /**
     * Check if contest can be cancelled
     */
    public function canBeCancelled(): bool
    {
        // Can only cancel upcoming or live contests, not completed or already cancelled
        return in_array($this->status, ['upcoming', 'live']) && !$this->isCancelled();
    }

    /**
     * Cancel the contest and refund all entry fees
     */
    public function cancel(string $reason = null): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        \DB::beginTransaction();

        try {
            // Get all lineups for this contest
            $lineups = $this->lineups()->with('user')->get();

            // Refund entry fees to all participants
            foreach ($lineups as $lineup) {
                $lineup->user->addPoints(
                    $this->entry_fee,
                    'refund',
                    "Refund for cancelled contest: {$this->name}",
                    $this->id
                );
            }

            // Update contest status
            $this->status = 'cancelled';
            $this->cancelled_at = now();
            $this->cancellation_reason = $reason;
            $this->save();

            \DB::commit();
            return true;

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Contest cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get total number of users affected by cancellation
     */
    public function getAffectedUsersCount(): int
    {
        return $this->lineups()->distinct('user_id')->count('user_id');
    }

    /**
     * Get total amount to be refunded
     */
    public function getTotalRefundAmount(): int
    {
        return $this->current_entries * $this->entry_fee;
    }
}
