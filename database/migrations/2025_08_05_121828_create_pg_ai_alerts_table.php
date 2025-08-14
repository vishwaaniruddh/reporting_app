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
        // Create the sequence with a higher start value to accommodate existing IDs
        DB::connection('pgsql')->statement('CREATE SEQUENCE ai_alerts_id_seq START WITH 1000000');
        
        Schema::connection('pgsql')->create('ai_alerts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('panelid', 10);
            $table->string('seqno', 100);
            $table->string('zone', 3);
            $table->string('alarm', 3);
            $table->dateTime('createtime');
            $table->dateTime('receivedtime')->nullable()->useCurrent();
            $table->string('comment', 500)->nullable();
            $table->char('status', 1)->default('O');
            $table->char('sendtoclient', 1)->nullable();
            $table->string('closedBy', 20)->nullable();
            $table->dateTime('closedtime')->nullable();
            $table->string('sendip', 15)->nullable();
            $table->string('alerttype', 50)->nullable();
            $table->char('location', 1)->nullable();
            $table->char('priority', 1)->nullable();
            $table->string('AlertUserStatus', 50)->nullable();
            
            $table->string('ATMCode', 50)->nullable();
            $table->text('File_loc')->nullable();
            $table->string('cms_ip', 15)->nullable();
            
            // Add indexes to match MySQL
            $table->index('panelid');
            $table->index('zone');
            $table->index('alarm');
            $table->index('createtime');
            $table->index('receivedtime');
            $table->index('status');
            $table->index('sendtoclient');
            $table->index('closedBy');
            $table->index('sendip');
            $table->index('ATMCode');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('ai_alerts');
        DB::connection('pgsql')->statement('DROP SEQUENCE IF EXISTS ai_alerts_id_seq');
    }
};
