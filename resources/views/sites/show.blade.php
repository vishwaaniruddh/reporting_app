@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Site Details</h2>
                <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back to Sites</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>{{ $site->ATMID }}</h3>
            <p class="mb-0">{{ $site->Customer }} - {{ $site->Bank }}</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Address</th>
                            <td>{{ $site->SiteAddress }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $site->City }}, {{ $site->State }}</td>
                        </tr>
                        <tr>
                            <th>Zone</th>
                            <td>{{ $site->Zone }}</td>
                        </tr>
                        <tr>
                            <th>Panel Make</th>
                            <td>{{ $site->Panel_Make }}</td>
                        </tr>
                        <tr>
                            <th>Panel IDs</th>
                            <td>
                                <div>Old: {{ $site->OldPanelID }}</div>
                                <div>New: {{ $site->NewPanelID }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th>DVR IP</th>
                            <td>{{ $site->DVRIP }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
