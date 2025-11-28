<?php

namespace Tests\Unit;

use App\Models\Contest;
use App\Models\Lineup;
use App\Models\LineupPlayer;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineupModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_lineup_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $lineup = Lineup::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $lineup->user);
        $this->assertEquals($user->id, $lineup->user->id);
    }

    public function test_lineup_belongs_to_contest(): void
    {
        $contest = Contest::factory()->create();
        $lineup = Lineup::factory()->create(['contest_id' => $contest->id]);

        $this->assertInstanceOf(Contest::class, $lineup->contest);
        $this->assertEquals($contest->id, $lineup->contest->id);
    }

    public function test_lineup_has_many_lineup_players(): void
    {
        $lineup = Lineup::factory()->create();
        $lineupPlayer = LineupPlayer::factory()->create(['lineup_id' => $lineup->id]);

        $this->assertTrue($lineup->lineupPlayers->contains($lineupPlayer));
    }

    public function test_lineup_has_players_through_lineup_players(): void
    {
        $lineup = Lineup::factory()->create();
        $player = Player::factory()->create();

        LineupPlayer::factory()->create([
            'lineup_id' => $lineup->id,
            'player_id' => $player->id
        ]);

        $this->assertTrue($lineup->players->contains($player));
    }

    public function test_lineup_calculates_total_salary(): void
    {
        $lineup = Lineup::factory()->create();

        $player1 = Player::factory()->create(['salary' => 8000]);
        $player2 = Player::factory()->create(['salary' => 7000]);

        LineupPlayer::factory()->create([
            'lineup_id' => $lineup->id,
            'player_id' => $player1->id
        ]);

        LineupPlayer::factory()->create([
            'lineup_id' => $lineup->id,
            'player_id' => $player2->id
        ]);

        $this->assertEquals(15000, $lineup->getTotalSalary());
    }

    public function test_lineup_calculates_total_fpts(): void
    {
        $lineup = Lineup::factory()->create();

        LineupPlayer::factory()->create([
            'lineup_id' => $lineup->id,
            'fpts' => 45.5
        ]);

        LineupPlayer::factory()->create([
            'lineup_id' => $lineup->id,
            'fpts' => 38.2
        ]);

        $this->assertEquals(83.7, $lineup->getTotalFpts());
    }

    public function test_lineup_has_unique_name_per_user_per_contest(): void
    {
        $user = User::factory()->create();
        $contest = Contest::factory()->create();

        $lineup1 = Lineup::factory()->create([
            'user_id' => $user->id,
            'contest_id' => $contest->id,
            'name' => 'My Lineup'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Lineup::factory()->create([
            'user_id' => $user->id,
            'contest_id' => $contest->id,
            'name' => 'My Lineup'
        ]);
    }
}
