<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\GamePlayerStat;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_has_player_stats_relationship(): void
    {
        $game = Game::factory()->create();
        $stat = GamePlayerStat::factory()->create(['game_id' => $game->id]);

        $this->assertTrue($game->playerStats->contains($stat));
    }

    public function test_game_is_simulated_when_status_is_completed(): void
    {
        $game = Game::factory()->create(['status' => 'completed']);
        $this->assertTrue($game->isSimulated());
    }

    public function test_game_is_not_simulated_when_status_is_scheduled(): void
    {
        $game = Game::factory()->create(['status' => 'scheduled']);
        $this->assertFalse($game->isSimulated());
    }

    public function test_game_determines_winner_correctly(): void
    {
        $game = Game::factory()->create([
            'visitor_team' => 'LAL',
            'home_team' => 'GSW',
            'visitor_score' => 110,
            'home_score' => 105,
            'status' => 'completed'
        ]);

        $this->assertEquals('LAL', $game->winner);
    }

    public function test_game_has_no_winner_when_not_completed(): void
    {
        $game = Game::factory()->create([
            'status' => 'scheduled',
            'visitor_score' => null,
            'home_score' => null
        ]);

        $this->assertNull($game->winner);
    }

    public function test_game_formats_start_time(): void
    {
        $game = Game::factory()->create([
            'start_time' => '2025-12-25 19:30:00'
        ]);

        $this->assertIsString($game->formatted_start_time);
    }

    public function test_game_can_be_reset(): void
    {
        $game = Game::factory()->create([
            'status' => 'completed',
            'visitor_score' => 110,
            'home_score' => 105,
            'simulated_at' => now()
        ]);

        $game->reset();

        $this->assertEquals('scheduled', $game->status);
        $this->assertNull($game->visitor_score);
        $this->assertNull($game->home_score);
        $this->assertNull($game->simulated_at);
    }
}
