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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('entry_fee');
            $table->integer('max_entries');
            $table->integer('current_entries')->default(0);
            $table->integer('prize_pool');
            $table->date('contest_date');
            $table->datetime('lock_time');
            $table->enum('status', ['upcoming', 'live', 'completed'])->default('upcoming');
            $table->enum('contest_type', ['50-50', 'GPP', 'H2H']);
            $table->timestamps();

            $table->index('contest_date');
            $table->index('lock_time');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
