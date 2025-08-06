<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('alerts_sync_status', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('last_synced_id')->default(0);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });

        // Set default last_synced_id to the greatest id from alerts table
        $maxId = DB::connection('pgsql')->table('alerts')->max('id') ?? 0;
        DB::connection('pgsql')->table('alerts_sync_status')->insert([
            'last_synced_id' => $maxId,
            'last_synced_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('alerts_sync_status');
    }
};