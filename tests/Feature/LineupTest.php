<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\Lineup;
use App\Models\LineupPlayer;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineupTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Contest $contest;
    protected $players;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->contest = Contest::factory()->create(['status' => 'open']);

        // Create players with different positions
        $this->players = [
            'PG' => Player::factory()->create(['position' => 'PG', 'salary' => 8000]),
            'SG' => Player::factory()->create(['position' => 'SG', 'salary' => 7000]),
            'SF' => Player::factory()->create(['position' => 'SF', 'salary' => 7500]),
            'PF' => Player::factory()->create(['position' => 'PF', 'salary' => 8500]),
            'C' => Player::factory()->create(['position' => 'C', 'salary' => 9000]),
            'G' => Player::factory()->create(['position' => 'G', 'salary' => 6000]),
            'F' => Player::factory()->create(['position' => 'F', 'salary' => 6500]),
            'UTIL' => Player::factory()->create(['position' => 'PG', 'salary' => 7500]),
        ];
    }

    public function test_user_can_view_lineups_index(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('lineups.index'));

        $response->assertStatus(200);
        $response->assertViewIs('lineups.index');
        $response->assertViewHas('lineups');
    }

    public function test_user_can_view_create_lineup_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('lineups.create', ['contest_id' => $this->contest->id]));

        $response->assertStatus(200);
        $response->assertViewIs('lineups.create');
        $response->assertViewHas('contest');
        $response->assertViewHas('players');
    }

    public function test_user_can_create_lineup(): void
    {
        $playerIds = [];
        foreach ($this->players as $position => $player) {
            $playerIds[] = $player->id;
        }

        $response = $this->actingAs($this->user)
            ->post(route('lineups.store'), [
                'contest_id' => $this->contest->id,
                'name' => 'Test Lineup',
                'players' => $playerIds
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('lineups', [
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id,
            'name' => 'Test Lineup'
        ]);
    }

    public function test_lineup_requires_exactly_8_players(): void
    {
        $playerIds = [$this->players['PG']->id, $this->players['SG']->id];

        $response = $this->actingAs($this->user)
            ->post(route('lineups.store'), [
                'contest_id' => $this->contest->id,
                'name' => 'Test Lineup',
                'players' => $playerIds
            ]);

        $response->assertSessionHasErrors();
    }

    public function test_lineup_cannot_exceed_salary_cap(): void
    {
        // Create expensive players
        $expensivePlayers = [];
        foreach (['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL'] as $position) {
            $expensivePlayers[] = Player::factory()->create([
                'position' => $position === 'UTIL' ? 'PG' : $position,
                'salary' => 12500
            ])->id;
        }

        $response = $this->actingAs($this->user)
            ->post(route('lineups.store'), [
                'contest_id' => $this->contest->id,
                'name' => 'Expensive Lineup',
                'players' => $expensivePlayers
            ]);

        $response->assertSessionHasErrors();
    }

    public function test_user_can_edit_lineup(): void
    {
        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('lineups.edit', $lineup));

        $response->assertStatus(200);
        $response->assertViewIs('lineups.edit');
        $response->assertViewHas('lineup');
    }

    public function test_user_can_update_lineup(): void
    {
        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id,
            'name' => 'Old Name'
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('lineups.update', $lineup), [
                'name' => 'New Name'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('New Name', $lineup->fresh()->name);
    }

    public function test_user_can_delete_lineup(): void
    {
        $lineup = Lineup::factory()->create([
            'user_id' => $this->user->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('lineups.destroy', $lineup));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('lineups', [
            'id' => $lineup->id
        ]);
    }

    public function test_user_cannot_edit_another_users_lineup(): void
    {
        $otherUser = User::factory()->create();
        $lineup = Lineup::factory()->create([
            'user_id' => $otherUser->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('lineups.edit', $lineup));

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_another_users_lineup(): void
    {
        $otherUser = User::factory()->create();
        $lineup = Lineup::factory()->create([
            'user_id' => $otherUser->id,
            'contest_id' => $this->contest->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('lineups.destroy', $lineup));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_lineups(): void
    {
        $response = $this->get(route('lineups.index'));
        $response->assertRedirect(route('login'));
    }
}
