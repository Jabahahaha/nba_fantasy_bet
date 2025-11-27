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
        // SQLite doesn't support ALTER COLUMN with CHECK constraints
        // We need to recreate the table with the new constraint

        // Step 1: Create new table with updated constraint
        DB::statement('
            CREATE TABLE contests_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name VARCHAR NOT NULL,
                entry_fee INTEGER NOT NULL,
                max_entries INTEGER NOT NULL,
                current_entries INTEGER NOT NULL DEFAULT 0,
                prize_pool INTEGER NOT NULL,
                contest_date DATE NOT NULL,
                lock_time DATETIME NOT NULL,
                status VARCHAR CHECK (status IN (\'upcoming\', \'live\', \'completed\', \'cancelled\')) NOT NULL DEFAULT \'upcoming\',
                contest_type VARCHAR CHECK (contest_type IN (\'50-50\', \'GPP\', \'H2H\')) NOT NULL,
                created_at DATETIME,
                updated_at DATETIME,
                max_entries_per_user INTEGER NOT NULL DEFAULT 150,
                cancelled_at DATETIME,
                cancellation_reason TEXT
            )
        ');

        // Step 2: Copy data from old table to new table
        DB::statement('
            INSERT INTO contests_new
            SELECT * FROM contests
        ');

        // Step 3: Drop old table
        DB::statement('DROP TABLE contests');

        // Step 4: Rename new table to original name
        DB::statement('ALTER TABLE contests_new RENAME TO contests');

        // Step 5: Recreate indexes
        DB::statement('CREATE INDEX contests_contest_date_index ON contests (contest_date)');
        DB::statement('CREATE INDEX contests_lock_time_index ON contests (lock_time)');
        DB::statement('CREATE INDEX contests_status_index ON contests (status)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate table with old constraint (without 'cancelled')
        DB::statement('
            CREATE TABLE contests_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name VARCHAR NOT NULL,
                entry_fee INTEGER NOT NULL,
                max_entries INTEGER NOT NULL,
                current_entries INTEGER NOT NULL DEFAULT 0,
                prize_pool INTEGER NOT NULL,
                contest_date DATE NOT NULL,
                lock_time DATETIME NOT NULL,
                status VARCHAR CHECK (status IN (\'upcoming\', \'live\', \'completed\')) NOT NULL DEFAULT \'upcoming\',
                contest_type VARCHAR CHECK (contest_type IN (\'50-50\', \'GPP\', \'H2H\')) NOT NULL,
                created_at DATETIME,
                updated_at DATETIME,
                max_entries_per_user INTEGER NOT NULL DEFAULT 150,
                cancelled_at DATETIME,
                cancellation_reason TEXT
            )
        ');

        DB::statement('INSERT INTO contests_new SELECT * FROM contests WHERE status != \'cancelled\'');
        DB::statement('DROP TABLE contests');
        DB::statement('ALTER TABLE contests_new RENAME TO contests');
        DB::statement('CREATE INDEX contests_contest_date_index ON contests (contest_date)');
        DB::statement('CREATE INDEX contests_lock_time_index ON contests (lock_time)');
        DB::statement('CREATE INDEX contests_status_index ON contests (status)');
    }
};
