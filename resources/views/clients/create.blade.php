@extends('layouts.app')

@section('content')
<div class="container-fluid">


<h1>Add New Client</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('clients.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Client Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Save Client</button>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div>
@endsection
