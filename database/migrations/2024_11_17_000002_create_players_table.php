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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('team', 3);
            $table->string('position'); // PG/SG/SF/PF/C
            $table->decimal('ppg', 4, 1);
            $table->decimal('rpg', 4, 1);
            $table->decimal('apg', 4, 1);
            $table->decimal('spg', 3, 1);
            $table->decimal('bpg', 3, 1);
            $table->decimal('topg', 3, 1);
            $table->decimal('mpg', 4, 1);
            $table->integer('salary');
            $table->boolean('is_playing')->default(true);
            $table->timestamps();

            $table->index('position');
            $table->index('team');
            $table->index('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
