<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Give existing users with low balance a boost to 10000
        DB::statement('UPDATE users SET points_balance = 10000 WHERE points_balance < 1000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for data update
    }
};
