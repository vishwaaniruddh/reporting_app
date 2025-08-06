@extends('layouts.app')

@section('title', 'Alert Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Alert Details</h2>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Alerts
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Alert Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Alert Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Alert ID</th>
                                    <td>{{ $alert->id }}</td>
                                </tr>
                                <tr>
                                    <th>Panel ID</th>
                                    <td>{{ $alert->panelid }}</td>
                                </tr>
                                <tr>
                                    <th>Zone</th>
                                    <td>{{ $alert->zone }}</td>
                                </tr>
                                <tr>
                                    <th>Alert Type</th>
                                    <td>{{ $alert->alerttype }}</td>
                                </tr>
                                <tr>
                                    <th>Created Time</th>
                                    <td>{{ $alert->createtime }}</td>
                                </tr>
                                <tr>
                                    <th>Received Time</th>
                                    <td>{{ $alert->receivedtime }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($alert->closedtime)
                                            <span class="badge bg-success">Closed</span>
                                            <br>
                                            <small>Closed by: {{ $alert->closedBy }}</small>
                                            <br>
                                            <small>At: {{ $alert->closedtime }}</small>
                                        @else
                                            <span class="badge bg-warning">Open</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($alert->comment)
                            <div class="alert alert-info">
                                <h5>Comment:</h5>
                                <p>{{ $alert->comment }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Site Information Card -->
            @if($alert->ATMID || $alert->Customer)
            <div class="card">
                <div class="card-header">
                    <h4>Site Information</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>ATM ID</th>
                            <td>{{ $alert->ATMID ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <td>{{ $alert->Customer ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Bank</th>
                            <td>{{ $alert->Bank ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $alert->SiteAddress ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $alert->City ?? 'N/A' }}, {{ $alert->State ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Panel Make</th>
                            <td>{{ $alert->Panel_Make ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    @if($alert->ATMID)
                    <a href="{{ route('sites.show', $alert->SN ?? 1) }}" class="btn btn-info btn-block mt-3">
                        <i class="fas fa-building"></i> View Full Site Details
                    </a>
                    @endif
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        No site information available for this alert.
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
