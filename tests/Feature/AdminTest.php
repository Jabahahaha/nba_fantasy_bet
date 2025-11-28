<?php

namespace Tests\Feature;

use App\Models\Contest;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_contest_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.contests.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.create-contest');
    }

    public function test_admin_can_create_contest(): void
    {
        $contestData = [
            'name' => 'Test Contest',
            'contest_type' => 'GPP',
            'entry_fee' => 100,
            'max_entries' => 100,
            'max_entries_per_user' => 150,
            'contest_date' => now()->addDays(1)->format('Y-m-d'),
            'lock_time' => now()->addDays(1)->format('Y-m-d\TH:i')
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.contests.store'), $contestData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contests', [
            'name' => 'Test Contest',
            'entry_fee' => 100
        ]);
    }

    public function test_admin_can_cancel_contest(): void
    {
        $contest = Contest::factory()->create(['status' => 'open']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.contests.cancel', $contest));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('cancelled', $contest->fresh()->status);
    }

    public function test_admin_can_view_games_management(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.games.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.games.index');
    }

    public function test_admin_can_simulate_game(): void
    {
        $game = Game::factory()->create([
            'status' => 'scheduled',
            'visitor_score' => null,
            'home_score' => null
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.games.simulate', $game));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $freshGame = $game->fresh();
        $this->assertEquals('completed', $freshGame->status);
        $this->assertNotNull($freshGame->visitor_score);
        $this->assertNotNull($freshGame->home_score);
    }

    public function test_admin_can_simulate_all_games_for_date(): void
    {
        $date = now()->format('Y-m-d');
        Game::factory()->count(3)->create([
            'game_date' => $date,
            'status' => 'scheduled'
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.games.simulate-date'), [
                'date' => $date
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $completedGames = Game::where('game_date', $date)
            ->where('status', 'completed')
            ->count();

        $this->assertEquals(3, $completedGames);
    }

    public function test_admin_can_reset_game(): void
    {
        $game = Game::factory()->create([
            'status' => 'completed',
            'visitor_score' => 110,
            'home_score' => 105
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.games.reset', $game));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $freshGame = $game->fresh();
        $this->assertEquals('scheduled', $freshGame->status);
        $this->assertNull($freshGame->visitor_score);
        $this->assertNull($freshGame->home_score);
    }

    public function test_admin_can_view_roster_manager(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.rosters.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.rosters.index');
    }

    public function test_admin_can_update_player_roster_status(): void
    {
        $player = Player::factory()->create([
            'roster_status' => 'bench',
            'roster_rank' => null,
            'is_playing' => false
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.rosters.update', $player), [
                'roster_status' => 'active',
                'roster_rank' => 1,
                'is_playing' => 1
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $freshPlayer = $player->fresh();
        $this->assertEquals('active', $freshPlayer->roster_status);
        $this->assertEquals(1, $freshPlayer->roster_rank);
        $this->assertTrue($freshPlayer->is_playing);
    }

    public function test_admin_can_auto_rebalance_rosters(): void
    {
        Player::factory()->count(15)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.rosters.rebalance'), [
                'top' => 10
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $activeCount = Player::where('roster_status', 'active')->count();
        $this->assertEquals(10, $activeCount);
    }

    public function test_admin_can_view_update_data_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.update.data'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.update-data');
    }

    public function test_regular_user_cannot_create_contest(): void
    {
        $contestData = [
            'name' => 'Test Contest',
            'contest_type' => 'GPP',
            'entry_fee' => 100,
            'max_entries' => 100
        ];

        $response = $this->actingAs($this->regularUser)
            ->post(route('admin.contests.store'), $contestData);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
