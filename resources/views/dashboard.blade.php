@extends('layouts.app')

        

@section('styles')
{{-- Add some custom styles for a more polished look --}}
<style>
    body {
        background-color: #f8f9fc;
    }
    .card-header-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 0.5em 0.75em;
    }
    .action-btn {
        transition: all 0.2s ease-in-out;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .table-responsive {
        max-height: 400px;
    }
    .icon-circle {
        height: 3rem;
        width: 3rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection


@section('content')
<div class="container-fluid py-4">
</div>
@endsection

@section('scripts')
{{-- Make sure Font Awesome is loaded in your main layout, e.g. layouts/app.blade.php --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" /> --}}
<script>
    // Simple script to add a confirmation before running syncs
    document.querySelectorAll('.action-btn').forEach(button => {
        button.closest('form').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to run this sync task?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
