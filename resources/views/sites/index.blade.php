@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h2>Sites</h2>
        </div>
        <div class="col-md-4">
            <form action="{{ route('sites.index') }}" method="GET" class="form-inline">
                <div class="input-group w-100">
                    <input type="text" name="search" class="form-control" placeholder="Search sites..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>SN</th>

                            <th>
                                <a href="{{ route('sites.index', ['sort' => 'ATMID', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    ATM ID
                                    @if(request('sort') == 'ATMID')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Customer</th>
                            <th>Bank</th>
                            <th>Location</th>
                            <th>Panel IDs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sites as $site)
                            <tr>
                                <td>{{ $site->SN }}</td>
                                <td>{{ $site->ATMID }}</td>
                                <td>{{ $site->Customer }}</td>
                                <td>{{ $site->Bank }}</td>
                                <td>{{ $site->City }}, {{ $site->State }}</td>
                                <td>
                                    <div>Old: {{ $site->OldPanelID }}</div>
                                    <div>New: {{ $site->NewPanelID }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('sites.show', $site->SN) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No sites found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $sites->links() }}
    </div>
</div>
@endsection
