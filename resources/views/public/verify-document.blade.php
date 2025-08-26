@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px; margin:40px auto;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Document Verification</h2>
            @if(!$valid)
                <div class="alert alert-danger">{{ $message ?? 'This document is invalid or expired.' }}</div>
            @else
                <div class="alert alert-success">This document is <strong>VALID</strong>.</div>
                <ul class="list-group mt-3 mb-3">
                    <li class="list-group-item"><strong>Document Type:</strong> {{ $document->document_type }}</li>
                    <li class="list-group-item"><strong>Resident:</strong> {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }}</li>
                    <li class="list-group-item"><strong>Date Issued:</strong> {{ $document->approved_at ? \Carbon\Carbon::parse($document->approved_at)->format('F j, Y') : 'N/A' }}</li>
                    @if($expiration)
                        <li class="list-group-item"><strong>Expiration:</strong> {{ \Carbon\Carbon::parse($expiration)->format('F j, Y') }}</li>
                    @endif
                    <li class="list-group-item"><strong>Status:</strong> {{ $valid ? 'Active' : 'Expired' }}</li>
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection 