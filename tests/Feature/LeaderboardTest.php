<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_leaderboards_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('leaderboards.index'));

        $response->assertStatus(200);
        $response->assertViewIs('leaderboards.index');
    }

    public function test_leaderboard_shows_top_winners(): void
    {
        // Create users with different winnings
        $winner1 = User::factory()->create(['total_winnings' => 10000]);
        $winner2 = User::factory()->create(['total_winnings' => 8000]);
        $winner3 = User::factory()->create(['total_winnings' => 5000]);

        $response = $this->actingAs($this->user)
            ->get(route('leaderboards.index'));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $winner1->name,
            $winner2->name,
            $winner3->name
        ]);
    }

    public function test_leaderboard_shows_most_active_players(): void
    {
        $activeUser = User::factory()->create();

        Contest::factory()->count(5)->create()->each(function ($contest) use ($activeUser) {
            ContestEntry::factory()->create([
                'user_id' => $activeUser->id,
                'contest_id' => $contest->id
            ]);
        });

        $response = $this->actingAs($this->user)
            ->get(route('leaderboards.index'));

        $response->assertStatus(200);
        $response->assertSee($activeUser->name);
    }

    public function test_guest_cannot_view_leaderboards(): void
    {
        $response = $this->get(route('leaderboards.index'));
        $response->assertRedirect(route('login'));
    }
}
