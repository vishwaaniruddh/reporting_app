@extends('layouts.app')

@section('content')
<h1>Client Details</h1>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <td>{{ $client->id }}</td>
    </tr>
    <tr>
        <th>Name</th>
        <td>{{ $client->name }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $client->status ? 'Active' : 'Inactive' }}</td>
    </tr>
</table>

<a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Edit</a>
<a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to list</a>
@endsection
