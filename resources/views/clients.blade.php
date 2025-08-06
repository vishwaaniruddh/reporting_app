@extends('layouts.app')

@section('content')
<h1>clients</h1>

<a href="{{ route('clients.create') }}" class="btn btn-primary">Add Customer</a>

@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Status</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->name }}</td>
            <td>{{ $client->status ? 'Active' : 'Inactive' }}</td>
            <td>
                <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm">View</a>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this customer?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $clients->links() }}
@endsection
