@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-danger">
        <h4>Access Denied</h4>
        <p>You don't have permission to access this page.</p>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
    </div>
</div>
@endsection
