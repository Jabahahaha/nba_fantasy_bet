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

    public function lineups()
    {
        return $this->hasMany(Lineup::class);
    }

    public function payouts()
    {
        return $this->hasMany(ContestPayout::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'upcoming' &&
               $this->lock_time->isFuture() &&
               $this->current_entries < $this->max_entries;
    }

    public function isLocked(): bool
    {
        return $this->lock_time->isPast() || $this->status !== 'upcoming';
    }

    public function getSecondsUntilLock(): int
    {
        if ($this->lock_time->isPast()) {
            return 0;
        }
        return $this->lock_time->diffInSeconds(now());
    }

    public function lock(): void
    {
        $this->status = 'live';
        $this->save();
    }

    public function calculatePrizes(): void
    {
        $this->payouts()->delete();

        $prizePool = $this->prize_pool;
        $totalEntries = $this->current_entries;

        if ($totalEntries == 0) {
            return;
        }

        switch ($this->contest_type) {
            case '50-50':
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
                ContestPayout::create([
                    'contest_id' => $this->id,
                    'position_min' => 1,
                    'position_max' => 1,
                    'payout_amount' => $prizePool,
                ]);
                break;

            case 'GPP':
                $payouts = [
                    [1, 1, 0.20],
                    [2, 2, 0.10],
                    [3, 3, 0.07],
                    [4, 5, 0.05],
                    [6, 10, 0.03],
                    [11, 20, 0.015],
                    [21, 30, 0.01],
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

    public function distributePrizes(): void
    {
        $payouts = $this->payouts;
        $lineups = $this->lineups()
            ->whereNotNull('final_rank')
            ->orderBy('final_rank')
            ->get();

        foreach ($lineups as $lineup) {
            $rank = $lineup->final_rank;

            $payout = $payouts->first(function ($p) use ($rank) {
                return $rank >= $p->position_min && $rank <= $p->position_max;
            });

            if ($payout) {
                $prizeAmount = $payout->payout_amount;
                $lineup->prize_won = $prizeAmount;
                $lineup->save();

                $lineup->user->addPoints(
                    $prizeAmount,
                    'contest_won',
                    "Won {$prizeAmount} points in {$this->name} (Rank: {$rank})",
                    $this->id
                );

                $lineup->user->total_winnings += $prizeAmount;
                $lineup->user->save();
            }
        }

        $this->status = 'completed';
        $this->save();
    }

    public function getUserEntryCount($userId): int
    {
        return $this->lineups()->where('user_id', $userId)->count();
    }

    public function canUserEnter($userId): bool
    {
        return $this->getUserEntryCount($userId) < $this->max_entries_per_user;
    }

    public function getUserRemainingEntries($userId): int
    {
        return max(0, $this->max_entries_per_user - $this->getUserEntryCount($userId));
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled' || $this->cancelled_at !== null;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['upcoming', 'live']) && !$this->isCancelled();
    }

    public function cancel(string $reason = null): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        \DB::beginTransaction();

        try {
            $lineups = $this->lineups()->with('user')->get();

            foreach ($lineups as $lineup) {
                $lineup->user->addPoints(
                    $this->entry_fee,
                    'refund',
                    "Refund for cancelled contest: {$this->name}",
                    $this->id
                );
            }

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

    public function getAffectedUsersCount(): int
    {
        return $this->lineups()->distinct('user_id')->count('user_id');
    }

    public function getTotalRefundAmount(): int
    {
        return $this->current_entries * $this->entry_fee;
    }
}
