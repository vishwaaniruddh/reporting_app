<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckSyncStatus extends Command
{
    protected $signature = 'sync:status {--table=alerts : Table to check status for}';
    protected $description = 'Check sync status for specified table';

    public function handle()
    {
        $table = $this->option('table');
        
        if ($table === 'alerts') {
            $this->checkAlertsSyncStatus();
        } else {
            $this->error("Unsupported table: {$table}");
            return 1;
        }

        return 0;
    }

    protected function checkAlertsSyncStatus()
    {
        try {
            $status = DB::connection('pgsql')
                ->table('alerts_sync_status')
                ->first();

            if (!$status) {
                $this->warn('⚠️  No sync status found. Run sync first.');
                return;
            }

            $this->info('📊 Alerts Sync Status:');
            $this->line('┌─────────────────────────────────────┐');
            $this->line('│ Last Synced ID: ' . str_pad($status->last_synced_id, 15) . ' │');
            $this->line('│ Last Synced At: ' . str_pad($status->last_synced_at ?? 'Never', 15) . ' │');
            $this->line('│ Updated At:     ' . str_pad($status->updated_at, 15) . ' │');
            $this->line('└─────────────────────────────────────┘');

            // Check for new records in MySQL
            $newRecords = DB::connection('mysql')
                ->table('alerts')
                ->where('id', '>', $status->last_synced_id)
                ->count();

            if ($newRecords > 0) {
                $this->warn("⚠️  {$newRecords} new records waiting to be synced");
            } else {
                $this->info('✅ All records are up to date');
            }

        } catch (\Exception $e) {
            $this->error('❌ Failed to check sync status: ' . $e->getMessage());
        }
    }
} 