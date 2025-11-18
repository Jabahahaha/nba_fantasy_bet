<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lineup_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lineup_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->string('position_slot'); // PG/SG/SF/PF/C/G/F/UTIL
            $table->integer('simulated_points')->nullable();
            $table->integer('simulated_rebounds')->nullable();
            $table->integer('simulated_assists')->nullable();
            $table->integer('simulated_steals')->nullable();
            $table->integer('simulated_blocks')->nullable();
            $table->integer('simulated_turnovers')->nullable();
            $table->decimal('fantasy_points_earned', 5, 2)->nullable();

            $table->index(['lineup_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineup_players');
    }
};
