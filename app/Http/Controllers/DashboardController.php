<?php 
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Postgres\PgReport;
use App\Models\Postgres\PgSite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use stdClass;

class DashboardController extends Controller
{
    public function index()
    {
        $lastSync = null;
        $syncHistory = collect();

        // Safely query sync_log table to prevent errors if it doesn't exist
        try {
            if (Schema::connection('pgsql')->hasTable('sync_log')) {
                $lastSync = DB::connection('pgsql')->table('sync_log')->latest('synced_at')->first();
                $syncHistory = DB::connection('pgsql')->table('sync_log')->latest('synced_at')->take(10)->get();
            }
        } catch (\Exception $e) {
            // Log the error but continue, as we can use dummy data
            Log::warning('Could not query sync_log table: ' . $e->getMessage());
        }

        // Fetch real-time stats from existing tables
        $totalSites = 0;
        $totalCustomers = 0;
        $totalAlerts = 0;

        try {
            if (Schema::connection('pgsql')->hasTable('sites')) {
                $totalSites = PgSite::on('pgsql')->count();
                $totalCustomers = PgSite::on('pgsql')->distinct()->count('Customer');
            }
            if (Schema::connection('pgsql')->hasTable('alerts')) {
                // PgReport model points to the 'alerts' table
                $totalAlerts = PgReport::on('pgsql')->count();
            }
        } catch (\Exception $e) {
            Log::warning('Could not query stats tables: ' . $e->getMessage());
            // Provide dummy stats as a fallback if tables don't exist
            $totalSites = 450;
            $totalCustomers = 25;
            $totalAlerts = 150234;
        }

        // If no sync history, create dummy data as requested for demonstration
        if ($syncHistory->isEmpty()) {
            $lastSync = new stdClass();
            $lastSync->records_synced = 12345;
            $lastSync->synced_at = Carbon::now()->subHour()->toDateTimeString();

            $syncHistory = collect([
                (object)['table_name' => 'ai_alerts', 'records_synced' => 5432, 'synced_at' => Carbon::now()->subHours(2)],
                (object)['table_name' => 'alerts', 'records_synced' => 6789, 'synced_at' => Carbon::now()->subHours(2)],
                (object)['table_name' => 'sites', 'records_synced' => 123, 'synced_at' => Carbon::now()->subDay()],
                (object)['table_name' => 'reports', 'records_synced' => 1, 'synced_at' => Carbon::now()->subDay()],
            ]);
        }
        
        // Prepare data for the sync history chart
        $chartData = $syncHistory->reverse()->mapWithKeys(function ($log) {
            return [Str::title(str_replace('_', ' ', $log->table_name)) => $log->records_synced];
        });

        return view('dashboard', [
            'lastSync' => $lastSync,
            'syncHistory' => $syncHistory,
            'totalSites' => $totalSites,
            'totalCustomers' => $totalCustomers,
            'totalAlerts' => $totalAlerts,
            'chartLabels' => $chartData->keys(),
            'chartValues' => $chartData->values(),
        ]);
    }
}
