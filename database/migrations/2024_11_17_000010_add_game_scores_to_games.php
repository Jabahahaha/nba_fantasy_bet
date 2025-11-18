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
        Schema::table('games', function (Blueprint $table) {
            $table->integer('visitor_score')->nullable()->after('visitor_team');
            $table->integer('home_score')->nullable()->after('home_team');
            $table->string('winner')->nullable()->after('status')->comment('Team abbreviation of winner');
            $table->timestamp('simulated_at')->nullable()->after('winner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['visitor_score', 'home_score', 'winner', 'simulated_at']);
        });
    }
};
