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
        Schema::create('game_player_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->integer('rebounds')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('steals')->default(0);
            $table->integer('blocks')->default(0);
            $table->integer('turnovers')->default(0);
            $table->decimal('fantasy_points', 8, 2)->default(0);
            $table->timestamps();

            // Ensure one stat record per player per game
            $table->unique(['game_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_player_stats');
    }
};
