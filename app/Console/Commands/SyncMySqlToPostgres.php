<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Automation\DataSync\MySqlToPostgresSync;
use Carbon\Carbon;

class SyncMySqlToPostgres extends Command
{
    protected $signature = 'sync:mysql-to-postgres
                          {--sites : Sync sites table}
                          {--alerts : Sync alerts table}
                          {--ai_alerts : Sync ai_alerts table}
                          {--ai_alerts_alive : Sync ai_alerts_alive table}
                          {--reports : Sync reports table}
                          {--all : Sync all tables}
                          {--from= : From date for alerts/reports (format: Y-m-d)}
                          {--to= : To date for alerts/reports (format: Y-m-d)}
                          {--force : Force full sync (ignore incremental)}
                          {--batch-size= : Custom batch size (default: 1000)}';

    protected $description = 'Sync data from MySQL to PostgreSQL with enhanced performance';

    protected $syncService;

    public function __construct(MySqlToPostgresSync $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $this->info('🚀 Starting enhanced MySQL to PostgreSQL sync...');
        
        $startTime = microtime(true);
        $totalSynced = 0;

        // Set custom batch size if provided
        if ($this->option('batch-size')) {
            $this->syncService->batchSize = (int) $this->option('batch-size');
            $this->info("📦 Using batch size: {$this->syncService->batchSize}");
        }

        $forceFull = $this->option('force');
        if ($forceFull) {
            $this->warn('⚠️  Force mode enabled - performing full sync');
        }

        try {
            // Sync Sites
            if ($this->option('sites') || $this->option('all')) {
                $this->info('📋 Syncing sites...');
                $totalSites = $this->syncService->syncSites();
                $this->info("✅ Synced {$totalSites} sites.");
                $totalSynced += $totalSites;
            }

            // Sync AI Alerts
            if ($this->option('ai_alerts') || $this->option('all')) {
                $fromDate = $this->option('from') ? Carbon::parse($this->option('from')) : null;
                $toDate = $this->option('to') ? Carbon::parse($this->option('to')) : null;

                $this->info('🤖 Syncing AI alerts...');
                $totalAiAlerts = $this->syncService->syncAiAlerts(
                    $fromDate, 
                    $toDate,
                    function($current, $total, $percentage) {
                        $this->output->write("\r📊 AI Alerts Progress: {$current}/{$total} ({$percentage}%)");
                    },
                    $forceFull
                );
                $this->info("\n✅ Completed! Synced {$totalAiAlerts} AI alerts.");
                $totalSynced += $totalAiAlerts;
            }

            if ($this->option('ai_alerts_alive') || $this->option('all')) {
                $fromDate = $this->option('from') ? Carbon::parse($this->option('from')) : null;
                $toDate = $this->option('to') ? Carbon::parse($this->option('to')) : null;

                $this->info('🤖 Syncing AI Alerts Alive ...');
                $totalAiAlerts = $this->syncService->syncAiAliveAlerts(
                    $fromDate, 
                    $toDate,
                    function($current, $total, $percentage) {
                        $this->output->write("\r📊 AI Alerts Alive Progress: {$current}/{$total} ({$percentage}%)");
                    },
                    $forceFull
                );
                $this->info("\n✅ Completed! Synced {$totalAiAlerts} AI alerts.");
                $totalSynced += $totalAiAlerts;
            }

            // Sync Alerts
            if ($this->option('alerts') || $this->option('all')) {
                $fromDate = $this->option('from') ? Carbon::parse($this->option('from')) : null;
                $toDate = $this->option('to') ? Carbon::parse($this->option('to')) : null;

                $this->info('🚨 Syncing alerts...');
                $totalAlerts = $this->syncService->syncAlerts(
                    $fromDate, 
                    $toDate,
                    function($current, $total, $percentage) {
                        $this->output->write("\r📊 Alerts Progress: {$current}/{$total} ({$percentage}%)");
                    },
                    $forceFull
                );
                $this->info("\n✅ Completed! Synced {$totalAlerts} alerts.");
                $totalSynced += $totalAlerts;
            }

            // Sync Reports
            if ($this->option('reports') || $this->option('all')) {
                $fromDate = $this->option('from') ? Carbon::parse($this->option('from')) : null;
                $toDate = $this->option('to') ? Carbon::parse($this->option('to')) : null;

                $this->info('📊 Syncing reports...');
                $totalReports = $this->syncService->syncReports($fromDate, $toDate);
                $this->info("✅ Synced {$totalReports} reports.");
                $totalSynced += $totalReports;
            }

            $elapsed = microtime(true) - $startTime;
            $this->info("🎉 Sync completed successfully!");
            $this->info("📈 Total records synced: {$totalSynced}");
            $this->info("⏱️  Total time: " . round($elapsed, 2) . " seconds");
            $this->info("⚡ Average rate: " . round($totalSynced / $elapsed, 2) . " records/sec");

        } catch (\Exception $e) {
            $this->error('❌ Sync failed: ' . $e->getMessage());
            $this->error('🔍 Check logs for more details');
            return 1;
        }

        return 0;
    }
}
