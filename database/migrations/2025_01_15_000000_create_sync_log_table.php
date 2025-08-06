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
        Schema::connection('pgsql')->create('sync_log', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->integer('records_synced');
            $table->timestamp('synced_at');
            $table->timestamps();
            
            $table->index(['table_name', 'synced_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('sync_log');
    }
}; 