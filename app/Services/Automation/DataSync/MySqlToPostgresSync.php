<?php

namespace App\Services\Automation\DataSync;

use App\Models\Report;
use App\Models\Site;
use App\Models\Postgres\PgReport;
use App\Models\Postgres\PgSite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class MySqlToPostgresSync
{
    public $batchSize = 1000; // Increased for better performance
    protected $maxExecutionTime = 3600; // 1 hour max execution
    protected $memoryLimit = '2G'; // 2GB memory limit

    public function __construct()
    {
        // Set execution limits
        ini_set('max_execution_time', $this->maxExecutionTime);
        ini_set('memory_limit', $this->memoryLimit);
        set_time_limit($this->maxExecutionTime);
    }

    /**
     * Enhanced sync for ai_alerts table with incremental updates
     */
    public function syncAiAlerts($fromDate = null, $toDate = null, $progress = null, $forceFull = false)
    {
        try {
            Log::info('Starting AI Alerts sync', [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'force_full' => $forceFull
            ]);

            // Get last synced timestamp from sync_log table
            $lastSyncedAt = $this->getLastSyncTimestamp('ai_alerts');
            
            if (!$forceFull && $lastSyncedAt) {
                $fromDate = $lastSyncedAt;
            }

            // Get total count for progress tracking
            $totalCount = DB::connection('mysql')
                ->table('ai_alerts')
                ->when($fromDate, fn($q) => $q->where('receivedtime', '>=', $fromDate))
                ->when($toDate, fn($q) => $q->where('receivedtime', '<=', $toDate))
                ->count();

            if ($totalCount === 0) {
                Log::info('No new AI alerts to sync');
                return 0;
            }

            $lastSyncedId = 0;
            $totalSynced = 0;
            $startTime = microtime(true);

            do {
                // Use cursor-based pagination for better performance
                $alerts = DB::connection('mysql')
                    ->table('ai_alerts')
                    ->select('*')
                    ->where('id', '>', $lastSyncedId)
                    ->when($fromDate, fn($q) => $q->where('receivedtime', '>=', $fromDate))
                    ->when($toDate, fn($q) => $q->where('receivedtime', '<=', $toDate))
                    ->orderBy('id')
                    ->limit($this->batchSize)
                    ->get();

                if ($alerts->isEmpty()) {
                    break;
                }

                // Use bulk insert for better performance
                $this->bulkInsertAiAlerts($alerts);

                $batchCount = $alerts->count();
                $totalSynced += $batchCount;
                $lastSyncedId = $alerts->last()->id;

                // Report progress
                if ($progress) {
                    $percentComplete = round(($totalSynced / $totalCount) * 100, 2);
                    $progress($totalSynced, $totalCount, $percentComplete);
                }

                // Log progress every 10,000 records
                if ($totalSynced % 10000 === 0) {
                    $elapsed = microtime(true) - $startTime;
                    $rate = $totalSynced / $elapsed;
                    Log::info("AI Alerts sync progress", [
                        'synced' => $totalSynced,
                        'total' => $totalCount,
                        'rate' => round($rate, 2) . ' records/sec'
                    ]);
                }

            } while (true);

            // Update sync log
            $this->updateSyncLog('ai_alerts', $totalSynced);

            $elapsed = microtime(true) - $startTime;
            Log::info('AI Alerts sync completed', [
                'total_synced' => $totalSynced,
                'elapsed_time' => round($elapsed, 2) . ' seconds',
                'rate' => round($totalSynced / $elapsed, 2) . ' records/sec'
            ]);

            return $totalSynced;

        } catch (Exception $e) {
            Log::error('AI Alerts sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Enhanced sync for alerts table with incremental updates using monitoring table
     */
    public function syncAlerts($fromDate = null, $toDate = null, $progress = null, $forceFull = false)
    {
        try {
            Log::info('Starting Alerts sync', [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'force_full' => $forceFull
            ]);

            // Get last synced ID from alerts_sync_status table
            $lastSyncedId = $this->getLastSyncedAlertId();
            
            if (!$forceFull && $lastSyncedId > 0) {
                Log::info("Resuming alerts sync from ID: {$lastSyncedId}");
            } else {
                $lastSyncedId = 0;
                Log::info("Starting fresh alerts sync");
            }

            // Get total count for progress tracking (only new records)
            $totalCount = DB::connection('mysql')
                ->table('alerts')
                ->where('id', '>', $lastSyncedId)
                ->when($fromDate, fn($q) => $q->where('createtime', '>=', $fromDate))
                ->when($toDate, fn($q) => $q->where('createtime', '<=', $toDate))
                ->count();

            if ($totalCount === 0) {
                Log::info('No new alerts to sync');
                return 0;
            }

            $totalSynced = 0;
            $startTime = microtime(true);
            $currentLastSyncedId = $lastSyncedId;

            do {
                // Fetch new alerts from MySQL (only records with ID > last_synced_id)
                $alerts = DB::connection('mysql')
                    ->table('alerts')
                    ->select('*')
                    ->where('id', '>', $currentLastSyncedId)
                    ->when($fromDate, fn($q) => $q->where('createtime', '>=', $fromDate))
                    ->when($toDate, fn($q) => $q->where('createtime', '<=', $toDate))
                    ->orderBy('id')
                    ->limit($this->batchSize)
                    ->get();

                if ($alerts->isEmpty()) {
                    break;
                }

                // Use bulk insert for better performance (no duplicate checking)
                $this->bulkInsertAlerts($alerts);

                $batchCount = $alerts->count();
                $totalSynced += $batchCount;
                $currentLastSyncedId = $alerts->last()->id;

                // Update monitoring table after each batch
                $this->updateAlertsSyncStatus($currentLastSyncedId);

                // Report progress
                if ($progress) {
                    $percentComplete = round(($totalSynced / $totalCount) * 100, 2);
                    $progress($totalSynced, $totalCount, $percentComplete);
                }

                // Log progress every 10,000 records
                if ($totalSynced % 10000 === 0) {
                    $elapsed = microtime(true) - $startTime;
                    $rate = $totalSynced / $elapsed;
                    Log::info("Alerts sync progress", [
                        'synced' => $totalSynced,
                        'total' => $totalCount,
                        'last_synced_id' => $currentLastSyncedId,
                        'rate' => round($rate, 2) . ' records/sec'
                    ]);
                }

            } while ($alerts->count() === $this->batchSize);

            $elapsed = microtime(true) - $startTime;
            $rate = $totalSynced / $elapsed;

            Log::info('Alerts sync completed', [
                'total_synced' => $totalSynced,
                'last_synced_id' => $currentLastSyncedId,
                'elapsed_time' => round($elapsed, 2) . ' seconds',
                'rate' => round($rate, 2) . ' records/sec'
            ]);

            return $totalSynced;

        } catch (Exception $e) {
            Log::error('Alerts sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Bulk insert for better performance
     */
    protected function bulkInsertAlerts($alerts)
    {
        $values = [];
        foreach ($alerts as $alert) {
            $values[] = [
                'id' => $alert->id,
                'panelid' => $alert->panelid,
                'seqno' => $alert->seqno,
                'zone' => $alert->zone,
                'alarm' => $alert->alarm,
                'alerttype' => $alert->alerttype,
                'createtime' => $alert->createtime,
                'receivedtime' => $alert->receivedtime,
                'closedtime' => $alert->closedtime,
                'closedBy' => $alert->closedBy,
                'comment' => $alert->comment,
                'sendip' => $alert->sendip,
                'sendtoclient' => $alert->sendtoclient,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Use PostgreSQL's COPY command for maximum performance
        if (count($values) > 0) {
            DB::connection('pgsql')->table('alerts')->insert($values);
        }
    }

    /**
     * Bulk insert for AI alerts
     */
    protected function bulkInsertAiAlerts($alerts)
    {
        $values = [];
        foreach ($alerts as $alert) {
            $values[] = [
                'id' => $alert->id,
                'panelid' => $alert->panelid,
                'seqno' => $alert->seqno,
                'zone' => $alert->zone,
                'alarm' => $alert->alarm,
                'alerttype' => $alert->alerttype,
                'createtime' => $alert->createtime,
                'receivedtime' => $alert->receivedtime,
                'closedtime' => $alert->closedtime,
                'closedBy' => $alert->closedBy,
                'comment' => $alert->comment,
                'sendip' => $alert->sendip,
                'sendtoclient' => $alert->sendtoclient,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (count($values) > 0) {
            DB::connection('pgsql')->table('ai_alerts')->insert($values);
        }
    }

    /**
     * Get last sync timestamp from sync log
     */
    protected function getLastSyncTimestamp($table)
    {
        try {
            $log = DB::connection('pgsql')
                ->table('sync_log')
                ->where('table_name', $table)
                ->orderBy('synced_at', 'desc')
                ->first();

            return $log ? $log->synced_at : null;
        } catch (Exception $e) {
            Log::warning("Could not get last sync timestamp for {$table}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Update sync log
     */
    protected function updateSyncLog($table, $recordsSynced)
    {
        try {
            DB::connection('pgsql')->table('sync_log')->insert([
                'table_name' => $table,
                'records_synced' => $recordsSynced,
                'synced_at' => now(),
                'created_at' => now()
            ]);
        } catch (Exception $e) {
            Log::warning("Could not update sync log for {$table}", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Sync sites table (existing method with improvements)
     */
    public function syncSites()
    {
        try {
            Log::info('Starting Sites sync');

            $lastSyncedId = 0;
            $totalSynced = 0;
            $startTime = microtime(true);

            do {
                $sites = DB::connection('mysql')
                          ->table('sites')
                          ->where('SN', '>', $lastSyncedId)
                          ->take($this->batchSize)
                          ->get();

                if ($sites->isEmpty()) {
                    break;
                }

                // Use bulk insert for better performance
                $this->bulkInsertSites($sites);

                $totalSynced += $sites->count();
                $lastSyncedId = $sites->last()->SN;

                // Log progress every 1,000 records
                if ($totalSynced % 1000 === 0) {
                    $elapsed = microtime(true) - $startTime;
                    $rate = $totalSynced / $elapsed;
                    Log::info("Sites sync progress", [
                        'synced' => $totalSynced,
                        'rate' => round($rate, 2) . ' records/sec'
                    ]);
                }

            } while (true);

            $elapsed = microtime(true) - $startTime;
            Log::info('Sites sync completed', [
                'total_synced' => $totalSynced,
                'elapsed_time' => round($elapsed, 2) . ' seconds'
            ]);

            return $totalSynced;

        } catch (Exception $e) {
            Log::error('Sites sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Bulk insert for sites
     */
    protected function bulkInsertSites($sites)
    {
        $values = [];
        foreach ($sites as $site) {
            $site = json_decode(json_encode($site), true);
            $values[] = [
                'SN' => $site['SN'],
                'Status' => $site['Status'],
                'Phase' => $site['Phase'],
                'Customer' => $site['Customer'],
                'Bank' => $site['Bank'],
                'ATMID' => $site['ATMID'],
                'ATMID_2' => $site['ATMID_2'],
                'ATMID_3' => $site['ATMID_3'],
                'ATMID_4' => $site['ATMID_4'],
                'TrackerNo' => $site['TrackerNo'],
                'ATMShortName' => $site['ATMShortName'],
                'SiteAddress' => $site['SiteAddress'],
                'City' => $site['City'],
                'State' => $site['State'],
                'Zone' => $site['Zone'],
                'Panel_Make' => $site['Panel_Make'],
                'OldPanelID' => $site['OldPanelID'],
                'NewPanelID' => $site['NewPanelID'],
                'DVRIP' => $site['DVRIP'],
                'DVRName' => $site['DVRName'],
                'DVR_Model_num' => $site['DVR_Model_num'],
                'Router_Model_num' => $site['Router_Model_num'],
                'UserName' => $site['UserName'],
                'Password' => $site['Password'],
                'live' => $site['live'],
                'current_dt' => $site['current_dt'],
                'mailreceive_dt' => $site['mailreceive_dt'],
                'eng_name' => $site['eng_name'],
                'addedby' => $site['addedby'],
                'editby' => $site['editby'],
                'site_remark' => $site['site_remark'],
                'PanelIP' => $site['PanelIP'],
                'AlertType' => $site['AlertType'],
                'live_date' => $site['live_date'],
                'RouterIp' => $site['RouterIp'],
                'last_modified' => $site['last_modified'],
                'partial_live' => $site['partial_live'],
                'CTS_LocalBranch' => $site['CTS_LocalBranch'],
                'installationDate' => $site['installationDate'],
                'old_atmid' => $site['old_atmid'],
                'auto_alert' => $site['auto_alert'],
                'project' => $site['project'],
                'comfortID' => $site['comfortID'],
                'panel_power_connection' => $site['panel_power_connection'],
                'router_port' => $site['router_port'],
                'dvr_port' => $site['dvr_port'],
                'panel_port' => $site['panel_port'],
                'server_ip' => $site['server_ip'],
                'unique_id' => $site['unique_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (count($values) > 0) {
            DB::connection('pgsql')->table('sites')->insert($values);
        }
    }

    /**
     * Sync reports table (existing method)
     */
    public function syncReports($fromDate = null, $toDate = null)
    {
        $query = Report::query();
        
        if ($fromDate) {
            $query->where('createtime', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('createtime', '<=', $toDate);
        }

        $lastSyncedId = 0;
        $totalSynced = 0;

        do {
            $reports = $query->where('id', '>', $lastSyncedId)
                            ->take($this->batchSize)
                            ->get();

            if ($reports->isEmpty()) {
                break;
            }

            DB::connection('pgsql')->transaction(function() use ($reports) {
                foreach ($reports as $report) {
                    PgReport::updateOrCreate(
                        ['id' => $report->id],
                        $report->toArray()
                    );
                }
            });

            $totalSynced += $reports->count();
            $lastSyncedId = $reports->last()->id;

        } while (true);

        return $totalSynced;
    }

    /**
     * Get last synced alert ID from monitoring table
     */
    protected function getLastSyncedAlertId()
    {
        try {
            $status = DB::connection('pgsql')
                ->table('alerts_sync_status')
                ->first();

            if (!$status) {
                // Create initial record if table is empty
                DB::connection('pgsql')
                    ->table('alerts_sync_status')
                    ->insert([
                        'last_synced_id' => 0,
                        'last_synced_at' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                return 0;
            }

            return $status->last_synced_id ?? 0;
        } catch (Exception $e) {
            Log::error('Failed to get last synced alert ID', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Update alerts sync status in monitoring table
     */
    protected function updateAlertsSyncStatus($lastSyncedId)
    {
        try {
            DB::connection('pgsql')
                ->table('alerts_sync_status')
                ->updateOrInsert(
                    ['id' => 1], // Always update the first record
                    [
                        'last_synced_id' => $lastSyncedId,
                        'last_synced_at' => now(),
                        'updated_at' => now()
                    ]
                );
        } catch (Exception $e) {
            Log::error('Failed to update alerts sync status', [
                'error' => $e->getMessage(),
                'last_synced_id' => $lastSyncedId
            ]);
        }
    }
}
