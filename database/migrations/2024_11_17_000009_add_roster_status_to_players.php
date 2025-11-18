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
        Schema::table('players', function (Blueprint $table) {
            $table->enum('roster_status', ['active', 'bench', 'inactive'])->default('active')->after('is_playing');
            $table->integer('roster_rank')->nullable()->after('roster_status')->comment('Rank within team by minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['roster_status', 'roster_rank']);
        });
    }
};
