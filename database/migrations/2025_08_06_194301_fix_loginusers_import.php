<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FixLoginusersImport extends Migration
{
    public function up()
    {
        // Verify tables exist
        if (!Schema::hasTable('users') || !Schema::hasTable('loginusers')) {
            Log::error('Migration failed: Required tables missing');
            return;
        }

        // Clear existing users (except those you want to keep)
        DB::table('users')->where('id', '>', 2)->delete();

        // Get count before migration
        $initialCount = DB::table('users')->count();
        Log::info("Starting user import. Initial user count: {$initialCount}");

        // Process records in smaller batches
        DB::table('loginusers')->orderBy('id')->chunk(100, function ($users) {
            $recordsToInsert = [];
            
            foreach ($users as $user) {
                // Skip if email already exists
                if (DB::table('users')->where('email', $user->uname)->exists()) {
                    Log::info("Skipped duplicate email: {$user->uname}");
                    continue;
                }

                $recordsToInsert[] = [
                    'name' => $user->name,
                    'email' => $user->uname,
                    'password' => $this->determinePassword($user->pwd),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($recordsToInsert)) {
                DB::table('users')->insert($recordsToInsert);
                Log::info("Inserted batch of " . count($recordsToInsert) . " users");
            }
        });

        $finalCount = DB::table('users')->count();
        Log::info("User import complete. Final user count: {$finalCount}");
    }

    protected function determinePassword($password)
    {
        // If password looks like it's already hashed (starts with $2y$)
        if (preg_match('/^\$2y\$.{56}$/', $password)) {
            return $password;
        }
        return Hash::make($password);
    }

    public function down()
    {
        // Optional: Define how to reverse this if needed
    }
}