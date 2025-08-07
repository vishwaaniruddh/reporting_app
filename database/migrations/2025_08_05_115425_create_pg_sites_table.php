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
        DB::connection('pgsql')->statement('CREATE SEQUENCE sites_sn_seq START WITH 1000');
        
        Schema::connection('pgsql')->create('sites', function (Blueprint $table) {
            $table->integer('SN')->primary();
            $table->string('Status', 22)->nullable();
            $table->string('Phase', 7)->nullable();
            $table->string('Customer', 50)->nullable();
            $table->string('Bank', 13)->nullable();
            $table->string('ATMID', 40)->nullable();
            $table->string('ATMID_2', 40)->nullable();
            $table->string('ATMID_3', 8)->nullable();
            $table->string('ATMID_4', 8)->nullable();
            $table->string('TrackerNo', 40)->nullable();
            $table->string('ATMShortName', 255)->nullable();
            $table->text('SiteAddress')->nullable();
            $table->string('City', 60)->nullable();
            $table->string('State', 25)->nullable();
            $table->string('Zone', 15)->nullable();
            $table->string('Panel_Make', 20)->nullable()->index();
            $table->string('OldPanelID', 6)->nullable()->index();
            $table->string('NewPanelID', 10)->nullable()->index();
            $table->string('DVRIP', 20)->nullable()->index();
            $table->string('DVRName', 15)->nullable();
            $table->string('DVR_Model_num', 250)->nullable();
            $table->string('Router_Model_num', 250)->nullable();
            $table->string('UserName', 20)->nullable();
            $table->string('Password', 10)->nullable();
            $table->char('live', 10)->default('N')->index();
            $table->dateTime('current_dt')->nullable();
            $table->dateTime('mailreceive_dt')->nullable();
            $table->string('eng_name', 50)->nullable();
            $table->string('addedby', 50)->nullable();
            $table->string('editby', 50)->nullable();
            $table->string('site_remark', 1000)->nullable();
            $table->string('PanelIP', 25)->nullable()->index();
            $table->string('AlertType', 10)->default('C');
            $table->date('live_date')->nullable()->index();
            $table->string('RouterIp', 50)->nullable();
            $table->integer('last_modified')->default(0);
            $table->integer('partial_live')->default(0);
            $table->string('CTS_LocalBranch', 200)->nullable();
            $table->dateTime('installationDate')->nullable();
            $table->string('old_atmid', 60)->nullable();
            $table->integer('auto_alert')->default(0)->index();
            $table->string('project', 20)->default('RMS');
            $table->string('comfortID', 20)->nullable();
            $table->string('panel_power_connection', 250)->nullable();
            $table->integer('router_port')->default(0);
            $table->integer('dvr_port')->default(0);
            $table->integer('panel_port')->default(0);
            $table->string('server_ip', 60)->default('0')->index();
            $table->string('unique_id', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::connection('pgsql')->dropIfExists('sites');
        // DB::connection('pgsql')->statement('DROP SEQUENCE IF EXISTS sites_sn_seq');
    }
};
