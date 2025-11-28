<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\Player;
use App\Services\GameSimulator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameSimulatorTest extends TestCase
{
    use RefreshDatabase;

    protected GameSimulator $simulator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simulator = app(GameSimulator::class);
    }

    public function test_simulator_can_simulate_game(): void
    {
        $game = Game::factory()->create([
            'visitor_team' => 'LAL',
            'home_team' => 'GSW',
            'status' => 'scheduled'
        ]);

        // Create players for both teams
        Player::factory()->count(5)->create(['team' => 'LAL', 'is_playing' => true]);
        Player::factory()->count(5)->create(['team' => 'GSW', 'is_playing' => true]);

        $this->simulator->simulateGame($game);

        $game->refresh();

        $this->assertEquals('completed', $game->status);
        $this->assertNotNull($game->visitor_score);
        $this->assertNotNull($game->home_score);
        $this->assertNotNull($game->simulated_at);
        $this->assertNotNull($game->winner);
    }

    public function test_simulator_generates_realistic_scores(): void
    {
        $game = Game::factory()->create([
            'visitor_team' => 'LAL',
            'home_team' => 'GSW',
            'status' => 'scheduled'
        ]);

        Player::factory()->count(8)->create(['team' => 'LAL', 'is_playing' => true]);
        Player::factory()->count(8)->create(['team' => 'GSW', 'is_playing' => true]);

        $this->simulator->simulateGame($game);

        $game->refresh();

        // NBA scores typically range from 90-130
        $this->assertGreaterThanOrEqual(80, $game->visitor_score);
        $this->assertLessThanOrEqual(150, $game->visitor_score);
        $this->assertGreaterThanOrEqual(80, $game->home_score);
        $this->assertLessThanOrEqual(150, $game->home_score);
    }

    public function test_simulator_creates_player_stats(): void
    {
        $game = Game::factory()->create([
            'visitor_team' => 'LAL',
            'home_team' => 'GSW',
            'status' => 'scheduled'
        ]);

        $players = Player::factory()->count(5)->create(['team' => 'LAL', 'is_playing' => true]);

        $this->simulator->simulateGame($game);

        $this->assertGreaterThan(0, $game->playerStats()->count());
    }

    public function test_simulator_can_simulate_multiple_games(): void
    {
        $date = now()->format('Y-m-d');

        $games = Game::factory()->count(3)->create([
            'game_date' => $date,
            'status' => 'scheduled'
        ]);

        foreach ($games as $game) {
            Player::factory()->count(5)->create(['team' => $game->visitor_team, 'is_playing' => true]);
            Player::factory()->count(5)->create(['team' => $game->home_team, 'is_playing' => true]);
        }

        $this->simulator->simulateDate($date);

        foreach ($games as $game) {
            $game->refresh();
            $this->assertEquals('completed', $game->status);
        }
    }

    public function test_simulator_does_not_resimulate_completed_games(): void
    {
        $game = Game::factory()->create([
            'status' => 'completed',
            'visitor_score' => 110,
            'home_score' => 105
        ]);

        $originalVisitorScore = $game->visitor_score;
        $originalHomeScore = $game->home_score;

        $this->simulator->simulateGame($game);

        $game->refresh();

        $this->assertEquals($originalVisitorScore, $game->visitor_score);
        $this->assertEquals($originalHomeScore, $game->home_score);
    }
}
