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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->date('game_date');
            $table->time('start_time');
            $table->string('visitor_team', 3);
            $table->string('home_team', 3);
            $table->string('arena')->nullable();
            $table->string('notes')->nullable();
            $table->enum('status', ['scheduled', 'live', 'completed'])->default('scheduled');
            $table->timestamps();

            $table->index('game_date');
            $table->index(['visitor_team', 'home_team']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
