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
        DB::connection('pgsql')->statement('CREATE SEQUENCE alerts_id_seq START WITH 1000000');
        
        Schema::connection('pgsql')->create('alerts', function (Blueprint $table) {
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
            $table->integer('level')->default(0);
            $table->string('sip2', 15)->nullable();
            $table->char('c_status', 1)->default('C');
            $table->integer('auto_alert')->default(0);
            $table->string('critical_alerts', 5)->default('n');
            $table->integer('Readstatus')->default(0);

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
            $table->index('level');
            $table->index('sip2');
            $table->index('auto_alert');
            $table->index('critical_alerts');
            $table->index('Readstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('alerts');
        DB::connection('pgsql')->statement('DROP SEQUENCE IF EXISTS alerts_id_seq');
    }
};
