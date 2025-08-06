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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- Loop through each client to display their details --}}
            @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        {{-- Action buttons for View, Edit, and Delete,
                             styled with Materialize 'btn-small' and color classes. --}}
                        <a href="{{ route('clients.show', $client) }}" class="btn-small waves-effect waves-light blue">View</a>
                        <a href="{{ route('clients.edit', $client) }}" class="btn-small waves-effect waves-light orange">Edit</a>
                        {{-- Form for deleting a client. The 'onsubmit' uses JavaScript's confirm for a prompt. --}}
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this client?');">
                            @csrf {{-- CSRF token for security --}}
                            @method('DELETE') {{-- Method spoofing for DELETE request --}}
                            <button type="submit" class="btn-small waves-effect waves-light red">Delete</button>
                        </form>
                    </td>
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
