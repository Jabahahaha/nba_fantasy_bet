<?php

namespace Tests\Unit;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContestModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_contest_has_entries_relationship(): void
    {
        $contest = Contest::factory()->create();
        $entry = ContestEntry::factory()->create(['contest_id' => $contest->id]);

        $this->assertTrue($contest->entries->contains($entry));
    }

    public function test_contest_has_games_relationship(): void
    {
        $contest = Contest::factory()->create();
        $game = Game::factory()->create(['game_date' => $contest->contest_date]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $contest->games);
    }

    public function test_contest_is_locked_when_status_is_locked(): void
    {
        $contest = Contest::factory()->create(['status' => 'locked']);
        $this->assertTrue($contest->isLocked());
    }

    public function test_contest_is_not_locked_when_status_is_open(): void
    {
        $contest = Contest::factory()->create(['status' => 'open']);
        $this->assertFalse($contest->isLocked());
    }

    public function test_contest_is_full_when_max_entries_reached(): void
    {
        $contest = Contest::factory()->create(['max_entries' => 2]);
        ContestEntry::factory()->count(2)->create(['contest_id' => $contest->id]);

        $this->assertTrue($contest->isFull());
    }

    public function test_contest_is_not_full_when_entries_below_max(): void
    {
        $contest = Contest::factory()->create(['max_entries' => 10]);
        ContestEntry::factory()->count(5)->create(['contest_id' => $contest->id]);

        $this->assertFalse($contest->isFull());
    }

    public function test_contest_calculates_prize_pool_correctly(): void
    {
        $contest = Contest::factory()->create(['entry_fee' => 100]);
        ContestEntry::factory()->count(10)->create(['contest_id' => $contest->id]);

        $this->assertEquals(1000, $contest->getPrizePool());
    }

    public function test_contest_can_determine_if_user_has_reached_max_entries(): void
    {
        $user = User::factory()->create();
        $contest = Contest::factory()->create(['max_entries_per_user' => 2]);

        ContestEntry::factory()->count(2)->create([
            'contest_id' => $contest->id,
            'user_id' => $user->id
        ]);

        $this->assertTrue($contest->userHasReachedMaxEntries($user));
    }

    public function test_contest_formats_contest_date_correctly(): void
    {
        $contest = Contest::factory()->create([
            'contest_date' => '2025-12-25'
        ]);

        $this->assertIsString($contest->formatted_date);
        $this->assertStringContainsString('December', $contest->formatted_date);
    }
}
