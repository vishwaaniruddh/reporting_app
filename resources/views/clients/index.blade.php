@extends('layouts.app')

@section('content')

{{-- Main container for the page content, using Materialize's 'container' class --}}
<div class="container">

    {{-- Button to add a new client, styled with Materialize classes for color and ripple effect --}}
    <a href="{{ route('clients.create') }}" class="btn waves-effect waves-light blue mb-3">Add Client</a>

    {{-- Display success message if available in the session.
         This uses a custom 'alert-success' class for styling,
         as Materialize typically uses Toasts for temporary messages. --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Container for the table to enable horizontal scrolling on small screens --}}
    <div class="table-container">
        {{-- Table for displaying client data, styled with Materialize classes:
             'striped' for alternating row colors,
             'highlight' for hover effect on rows,
             'responsive-table' for horizontal scrolling on smaller screens. --}}
        <table class="striped highlight responsive-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
            {{-- Loop through each client to display their details --}}
            @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->status ? 'Active' : 'Inactive' }}</td>
                    
                </tr>
            @empty
                {{-- Message displayed if no clients are found --}}
                <tr><td colspan="4">No clients found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
