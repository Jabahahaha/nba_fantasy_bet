<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Game;
use App\Models\Lineup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContestTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Contest $contest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'points_balance' => 1000
        ]);

        $this->contest = Contest::factory()->create([
            'entry_fee' => 100,
            'max_entries' => 10,
            'status' => 'open'
        ]);
    }

    public function test_user_can_view_contest_lobby(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('contests.index'));

        $response->assertStatus(200);
        $response->assertViewIs('contests.index');
        $response->assertViewHas('contests');
    }

    public function test_user_can_view_contest_details(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('contests.show', $this->contest));

        $response->assertStatus(200);
        $response->assertViewIs('contests.show');
        $response->assertViewHas('contest');
    }

    public function test_user_can_enter_contest_with_sufficient_balance(): void
    {
        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('contests.enter', $this->contest), [
                'lineup_id' => $lineup->id
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('contest_entries', [
            'contest_id' => $this->contest->id,
            'user_id' => $this->user->id,
            'lineup_id' => $lineup->id
        ]);

        $this->assertEquals(900, $this->user->fresh()->points_balance);
    }

    public function test_user_cannot_enter_contest_with_insufficient_balance(): void
    {
        $this->user->update(['points_balance' => 50]);

        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('contests.enter', $this->contest), [
                'lineup_id' => $lineup->id
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('contest_entries', [
            'contest_id' => $this->contest->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_user_cannot_enter_locked_contest(): void
    {
        $this->contest->update(['status' => 'locked']);

        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('contests.enter', $this->contest), [
                'lineup_id' => $lineup->id
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_exceed_max_entries_per_user(): void
    {
        $this->contest->update(['max_entries_per_user' => 1]);

        // Create first entry
        ContestEntry::factory()->create([
            'contest_id' => $this->contest->id,
            'user_id' => $this->user->id
        ]);

        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('contests.enter', $this->contest), [
                'lineup_id' => $lineup->id
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_view_contest_history(): void
    {
        ContestEntry::factory()->create([
            'contest_id' => $this->contest->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('contests.history'));

        $response->assertStatus(200);
        $response->assertViewIs('contests.history');
        $response->assertViewHas('entries');
    }

    public function test_user_can_view_contest_leaderboard(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('contests.leaderboard', $this->contest));

        $response->assertStatus(200);
        $response->assertViewIs('contests.leaderboard');
        $response->assertViewHas('entries');
    }

    public function test_guest_cannot_access_contests(): void
    {
        $response = $this->get(route('contests.index'));
        $response->assertRedirect(route('login'));
    }
}
