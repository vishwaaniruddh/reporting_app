@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 text-primary">Sites Management</h2>
            <div class="d-flex align-items-center">
                <form action="{{ route('sites.index') }}" method="GET" class="me-3 d-flex">
                    <div class="input-group input-group-sm rounded-pill overflow-hidden shadow-sm">
                        <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Search..."
                            value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                    </div>
                </form>
                @can('sites.create')
                    <a href="{{ route('sites.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fas fa-plus me-1"></i> Add New Site
                    </a>
                @endcan
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mb-0">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th scope="col" class="text-muted border-bottom">#</th>
                                <th scope="col" class="border-bottom">
                                    <a href="{{ route('sites.index', ['sort' => 'ATMID', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center">
                                        ATM ID
                                        @if(request('sort') == 'ATMID')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1 text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-bottom">Old Panel IDs</th>
                                <th scope="col" class="border-bottom">New Panel IDs</th>
                                <th scope="col" class="border-bottom">Customer</th>
                                <th scope="col" class="border-bottom">Bank</th>
                                <th scope="col" class="border-bottom">City</th>
                                <th scope="col" class="border-bottom">State</th>
                                <th scope="col" class="border-bottom">DVR</th>
                                <th scope="col" class="border-bottom">DVRIP</th>
                                <th scope="col" class="border-bottom">Location</th>
                                <th scope="col" class="text-center border-bottom">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sites as $index => $site)
                                <tr>
                                    <td class="text-muted">{{ $sites->firstItem() + $index }}</td>
                                    <td><span class="fw-semibold">{{ $site->ATMID }}</span></td>
                                    <td>
                                        <span class="badge bg-secondary me-1">{{ $site->OldPanelID }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $site->NewPanelID }}</span>


                                    </td>
                                    <td>{{ Str::limit($site->Customer, 20) }}</td>
                                    <td>{{ Str::limit($site->Bank, 15) }}</td>
                                    <td>
                                        {{ $site->City }}
                                    </td>
                                    <td>{{ $site->State }}</td>
                                    <td>{{ $site->DVRName }}</td>
                                    <td>{{ $site->DVRIP }}</td>
                                    
                                    <td>
                                        <span>{{ $site->SiteAddress }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('sites.show', $site->SN) }}"
                                            class="btn btn-sm btn-light text-primary me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('sites.edit')
                                            <a href="{{ route('sites.edit', $site->SN) }}"
                                                class="btn btn-sm btn-light text-success me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('sites.delete')
                                            <form action="{{ route('sites.destroy', $site->SN) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this site?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3 text-light"></i>
                                            <p class="h6">No sites found.</p>
                                            <p class="text-muted">It looks like there are no sites to display.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($sites->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $sites->firstItem() }} to {{ $sites->lastItem() }} of {{ $sites->total() }} entries
                </div>
                <div>
                    {{ $sites->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            white-space: nowrap;
        }

        .table tbody td {
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .input-group.rounded-pill {
            border-radius: 50rem !important;
            width: 250px;
        }

        .btn-light:hover {
            background-color: #e9ecef;
        }
    </style>
@endpush