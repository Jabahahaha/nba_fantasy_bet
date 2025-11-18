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
        Schema::create('lineups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->string('lineup_name')->nullable();
            $table->integer('total_salary_used');
            $table->decimal('fantasy_points_scored', 5, 2)->nullable();
            $table->integer('final_rank')->nullable();
            $table->integer('prize_won')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'contest_id']);
            $table->index('final_rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineups');
    }
};
