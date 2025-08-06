@extends('layouts.app')

@section('styles')
{{-- Add some custom styles for a more polished look --}}
<style>
    body {
        background-color: #f8f9fc;
    }
    .card-header-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 0.5em 0.75em;
    }
    .action-btn {
        transition: all 0.2s ease-in-out;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .table-responsive {
        max-height: 400px;
    }
    .icon-circle {
        height: 3rem;
        width: 3rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">



    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Synchronization Dashboard</h1>
        <form method="POST" action="{{-- route('sync.run.all') --}}">
            @csrf
            <button type="submit" class="btn btn-primary shadow-sm action-btn">
                <i class="fas fa-sync-alt fa-sm text-white-50 mr-2"></i> Run Full Sync
            </button>
        </form>
    </div>

    <!-- New Summary Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Last Sync Time</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{-- Assumes $lastSync is passed from controller --}}
                                {{ $lastSync ? \Carbon\Carbon::parse($lastSync->synced_at)->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Records Synced (Last Run)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $lastSync ? number_format($lastSync->records_synced) : '0' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sync Status</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    {{-- This could be dynamic based on a cache/job status --}}
                                    <span class="badge badge-success status-badge">Idle</span>
                                    {{-- <span class="badge badge-warning status-badge">Running...</span> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Sync Controls -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs card-header-icon text-primary"></i>
                        Manual Sync Controls
                    </h6>
                </div>
                <div class="card-body text-center">
                    <p>Trigger a synchronization task for a specific table. Use with caution during peak hours.</p>
                    <div class="d-flex justify-content-around flex-wrap">
                        {{-- These forms would trigger the sync command for each table --}}
                        <form method="POST" action="{{-- route('sync.run.sites') --}}" class="m-2"><button type="submit" class="btn btn-outline-secondary action-btn"><i class="fas fa-sitemap mr-2"></i>Sync Sites</button></form>
                        <form method="POST" action="{{-- route('sync.run.alerts') --}}" class="m-2"><button type="submit" class="btn btn-outline-secondary action-btn"><i class="fas fa-bell mr-2"></i>Sync Alerts</button></form>
                        <form method="POST" action="{{-- route('sync.run.ai_alerts') --}}" class="m-2"><button type="submit" class="btn btn-outline-secondary action-btn"><i class="fas fa-robot mr-2"></i>Sync AI Alerts</button></form>
                        <form method="POST" action="{{-- route('sync.run.reports') --}}" class="m-2"><button type="submit" class="btn btn-outline-secondary action-btn"><i class="fas fa-chart-bar mr-2"></i>Sync Reports</button></form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sync History -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history card-header-icon text-primary"></i>
                        Recent Sync History
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Table Name</th>
                                    <th>Records Synced</th>
                                    <th>Sync Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($syncHistory as $log)
                                <tr>
                                    <td><span class="badge badge-pill badge-info">{{ str_replace('_', ' ', Str::title($log->table_name)) }}</span></td>
                                    <td>{{ number_format($log->records_synced) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->synced_at)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No sync history found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Make sure Font Awesome is loaded in your main layout, e.g. layouts/app.blade.php --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" /> --}}
<script>
    // Simple script to add a confirmation before running syncs
    document.querySelectorAll('.action-btn').forEach(button => {
        button.closest('form').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to run this sync task?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
