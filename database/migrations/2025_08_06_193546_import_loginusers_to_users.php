<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ImportLoginusersToUsers extends Migration
{
    public function up()
    {
        // Safety check - only proceed if users table exists
        if (!Schema::hasTable('users')) {
            echo "Users table doesn't exist. Please run Laravel's default migrations first.\n";
            return;
        }

        // Get all user records from the old table
        $existingUsers = DB::table('loginusers')->get();

        // Prepare data for insertion
        $newUsers = [];
        $now = now(); // Current timestamp

        foreach ($existingUsers as $user) {
            $newUsers[] = [
                'name' => $user->name,
                'email' => $user->uname,
                
                // IMPORTANT: Check if passwords are hashed already
                // If unsure, use Hash::make($user->pwd) instead
                'password' => Hash::make($user->pwd),
                
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert in batches for better performance
        foreach (array_chunk($newUsers, 200) as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }

    public function down()
    {
        // This will empty the users table if you rollback
        // DB::table('users')->truncate();
    }
}