@extends('layouts.app')

@section('meta-tags')
    <title>{{ $title ?? 'Error' }} | TikTok Viewer</title>
    <meta name="description" content="An error occurred while processing your request.">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h1 class="h4">{{ $title ?? 'Error' }}</h1>
            <p>{{ $message ?? 'An unexpected error occurred while processing your request.' }}</p>
        </div>

        @if(config('app.debug') && isset($details))
            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h2 class="h5 mb-0">Debug Information</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h3 class="h6">Request Details</h3>
                        <ul class="list-group">
                            <li class="list-group-item">URL: {{ url()->current() }}</li>
                            <li class="list-group-item">Method: {{ request()->method() }}</li>
                            <li class="list-group-item">IP: {{ request()->ip() }}</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="h6">Error Details</h3>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    </div>

                    @if(isset($details['exception']))
                        <div class="mb-3">
                            <h3 class="h6">Exception</h3>
                            <div class="bg-light p-3 rounded">
                                <strong>Type:</strong> {{ $details['exception'] }}<br>
                                @if(isset($details['file']))
                                    <strong>File:</strong> {{ $details['file'] }} (line {{ $details['line'] ?? 'unknown' }})<br>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
            <button onclick="window.history.back();" class="btn btn-outline-secondary ml-2">Go Back</button>
        </div>
    </div>
@endsection 