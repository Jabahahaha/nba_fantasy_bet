<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user if it doesn't exist
        $adminEmail = 'admin@nba-fantasy-bet.test';

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make('password'), // Change this in production!
                'is_admin' => true,
                'points_balance' => 10000, // Give admin some points for testing
                'email_verified_at' => now(),
            ]);

            $this->command->info("✅ Admin user created:");
            $this->command->info("   Email: {$adminEmail}");
            $this->command->info("   Password: password");
            $this->command->warn("⚠️  IMPORTANT: Change the password in production!");
        } else {
            $this->command->info("ℹ️  Admin user already exists.");
        }
    }
}
