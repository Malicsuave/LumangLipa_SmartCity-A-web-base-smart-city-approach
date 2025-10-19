@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.documents') }}">Documents</a></li>
<li class="breadcrumb-item active" aria-current="page">Reports</li>
@endsection

@section('page-title', 'Document Requests Report')
@section('page-subtitle', 'Generate comprehensive reports for document requests')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Document Requests Report</h1>
                <p class="text-muted mb-0">Comprehensive report of all document requests</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.documents') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Documents
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="card-title">Document Requests Report Data</strong>
                    <div>
                        <span class="badge badge-info">{{ $documentRequests->count() }} Total Requests</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($documentRequests->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="documentsReportTable" data-export-title="Document Requests Report">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Resident Name</th>
                                <th>Barangay ID</th>
                                <th>Document Type</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Contact Number</th>
                                <th>Date Requested</th>
                                <th>Date Processed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentRequests as $request)
                                <tr>
                                    <td><strong>#{{ $request->id }}</strong></td>
                                    <td>
                                        @if($request->resident)
                                            {{ $request->resident->last_name }}, {{ $request->resident->first_name }} {{ $request->resident->middle_name ? substr($request->resident->middle_name, 0, 1) . '.' : '' }}
                                        @else
                                            {{ $request->requester_name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->resident)
                                            {{ $request->resident->barangay_id }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ ucwords(str_replace('_', ' ', $request->document_type)) }}</td>
                                    <td>{{ $request->purpose ?? 'N/A' }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @elseif($request->status == 'claimed')
                                            <span class="badge badge-info">Claimed</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($request->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->resident && $request->resident->contact_number)
                                            {{ $request->resident->contact_number }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        {{ $request->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($request->updated_at != $request->created_at)
                                            {{ $request->updated_at->format('M d, Y') }}
                                            <br><small class="text-muted">{{ $request->updated_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Not processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Request ID</th>
                                <th>Resident Name</th>
                                <th>Barangay ID</th>
                                <th>Document Type</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Contact Number</th>
                                <th>Date Requested</th>
                                <th>Date Processed</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="text-center">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No document requests found</h5>
                            <p class="text-muted">There are no document requests to generate a report.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script>
    // Initialize DataTable
    document.addEventListener('DOMContentLoaded', function() {
        if (window.DataTableHelpers) {
            DataTableHelpers.initDataTable('#documentsReportTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 7, 'desc' ]] // Sort by date requested descending
            });
        }
    });
</script>
@endpush
