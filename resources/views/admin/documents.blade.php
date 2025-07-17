@extends('layouts.admin.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-common.css') }}">
<style>
/* Smooth scroll behavior for the entire page */
html {
    scroll-behavior: smooth;
}


.filter-btn-hover {
    transition: transform 0.2s ease-in-out;
}

.filter-btn-hover:hover {
    transform: scale(1.1);
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
}

.filter-btn-hover:focus {
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
    box-shadow: none !important;
}

/* Smooth transitions for table elements */
.table-responsive {
    transition: all 0.3s ease;
}

/* Enhanced focus and smooth transitions */
.table thead a {
    transition: all 0.2s ease;
}

.table thead a:hover {
    transform: translateY(-1px);
}

/* Enhanced dropdown animations */
.dropdown-menu {
    transition: all 0.2s ease;
}

.dropdown-action-btn:focus:not(:focus-visible) {
    outline: none;
    box-shadow: none;
}

.section-title {
    font-weight: 700;
    color: #22223b;
    font-size: 1.08rem;
    margin-bottom: 12px;
    margin-top: 8px;
    letter-spacing: 0.01em;
}
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    border-bottom: 1px solid #f3f4f6;
}
.info-label {
    color: #6366f1;
    font-weight: 600;
    min-width: 120px;
    flex: 0 0 45%;
    font-size: 1rem;
}
.info-value {
    color: #22223b;
    font-weight: 400;
    flex: 1;
    text-align: right;
    font-size: 1rem;
    word-break: break-word;
}
.purpose-box {
    background: #f3f4f6;
    border-radius: 8px;
    padding: 14px 16px;
    color: #374151;
    font-size: 1rem;
    margin-top: 4px;
    border: 1px solid #e5e7eb;
}
/* Remove global ellipsis/max-width for all table cells */
.table th, .table td {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: unset !important;
    max-width: none !important;
}
/* Only apply ellipsis and max-width to Actions column */
.table-actions-col {
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    max-width: 120px;
}
.table-responsive,
.card-body,
.collapse,
#filterSection {
    overflow: visible !important;
}
.dropdown-menu {
    z-index: 9999 !important;
}
.admin-modal-body-iframe {
    height: 90vh;
    min-height: 600px;
    padding: 0;
}
.admin-iframe-full {
    width: 100%;
    height: 90vh;
    min-height: 600px;
    border: none;
    display: block;
}
</style>
@endpush


@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Document Requests</h1>
                <p class="text-muted mb-0">Manage barangay document requests and approvals</p>
            </div>
        </div>
    </div>
</div>


<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card document-metric-card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm metric-icon" style="background: #22c55e;">
                            <i class="fe fe-16 fe-file-text text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Total Requests</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['total'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card document-metric-card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm metric-icon" style="background: #f59e42;">
                            <i class="fe fe-16 fe-clock text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Pending Review</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['pending'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card document-metric-card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm metric-icon" style="background: #3b82f6;">
                            <i class="fe fe-16 fe-check-circle text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Approved</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['approved'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card document-metric-card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm metric-icon" style="background: #06b6d4;">
                            <i class="fe fe-16 fe-check-square text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Claimed</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['claimed'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card document-metric-card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm metric-icon" style="background: #ef4444;">
                            <i class="fe fe-16 fe-x-circle text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Rejected</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['rejected'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Manage Document Requests</strong>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <form action="{{ route('admin.documents') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search by resident name, document type, purpose..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#filterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                                        <i class="fe fe-filter fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(request()->hasAny(['search', 'status', 'document_type', 'date_from', 'date_to']))
                                <a href="{{ route('admin.documents') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['status', 'document_type', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter document requests by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">All Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
                                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Document Type</label>
                                        <select name="document_type" class="form-control form-control-sm">
                                            <option value="">All Types</option>
                                            <option value="Barangay Clearance" {{ request('document_type') == 'Barangay Clearance' ? 'selected' : '' }}>Barangay Clearance</option>
                                            <option value="Certificate of Residency" {{ request('document_type') == 'Certificate of Residency' ? 'selected' : '' }}>Certificate of Residency</option>
                                            <option value="Certificate of Indigency" {{ request('document_type') == 'Certificate of Indigency' ? 'selected' : '' }}>Certificate of Indigency</option>
                                            <option value="Certificate of Low Income" {{ request('document_type') == 'Certificate of Low Income' ? 'selected' : '' }}>Certificate of Low Income</option>
                                            <option value="Business Permit" {{ request('document_type') == 'Business Permit' ? 'selected' : '' }}>Business Permit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Date From</label>
                                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Date To</label>
                                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <!-- Filter Actions -->
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['search', 'status', 'document_type', 'date_from', 'date_to']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $documentRequests->total() }} results found</span>
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('status'))
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                        Status: {{ ucfirst(request('status')) }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('document_type'))
                                    <a href="{{ request()->fullUrlWithQuery(['document_type' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                        Type: {{ request('document_type') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('date_from'))
                                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null]) }}" class="badge badge-warning mr-1 text-decoration-none">
                                        From: {{ request('date_from') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('date_to'))
                                    <a href="{{ request()->fullUrlWithQuery(['date_to' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        To: {{ request('date_to') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
                
                @if($documentRequests->count() > 0)
                <div class="table-responsive">
                    <table id="resultsTable" class="table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('sort') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        ID
                                        @if(request('sort') == 'id')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Resident</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'document_type', 'direction' => request('sort') == 'document_type' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Document Type
                                        @if(request('sort') == 'document_type')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'requested_at', 'direction' => request('sort') == 'requested_at' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Date Requested
                                        @if(request('sort') == 'requested_at')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Status
                                        @if(request('sort') == 'status')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center table-actions-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentRequests as $request)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                    $dropdownItems = [];
                                    $dropdownItems[] = [
                                        'label' => 'View Details',
                                        'icon' => 'fe fe-eye fe-16 text-primary',
                                        'class' => 'view-details',
                                        'attrs' => 'data-id="' . $request->id . '"',
                                    ];
                                    if ($request->status === 'pending') {
                                        $dropdownItems[] = [
                                            'label' => 'Approve',
                                            'icon' => 'fe fe-check-circle fe-16 text-success',
                                            'class' => 'approve-request',
                                            'attrs' => 'data-id="' . $request->id . '"',
                                        ];
                                        $dropdownItems[] = [
                                            'label' => 'Reject',
                                            'icon' => 'fe fe-x-circle fe-16 text-danger',
                                            'class' => 'reject-request',
                                            'attrs' => 'data-id="' . $request->id . '"',
                                        ];
                                    } elseif ($request->status === 'approved') {
                                        $dropdownItems[] = [
                                            'label' => 'View Document',
                                            'icon' => 'fe fe-file-text fe-16 text-info',
                                            'class' => 'view-document',
                                            'attrs' => 'data-id="' . $request->id . '"',
                                        ];
                                        $dropdownItems[] = [
                                            'label' => 'Print Document',
                                            'icon' => 'fe fe-printer fe-16 text-secondary',
                                            'class' => 'print-document',
                                            'attrs' => 'data-id="' . $request->id . '"',
                                        ];
                                        $dropdownItems[] = [
                                            'label' => 'Mark as Claimed',
                                            'icon' => 'fe fe-check-square fe-16 text-success',
                                            'class' => 'mark-claimed',
                                            'attrs' => 'data-id="' . $request->id . '"',
                                        ];
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->resident ? $request->resident->first_name . ' ' . ($request->resident->middle_name ? $request->resident->middle_name . ' ' : '') . $request->resident->last_name : 'Unknown' }}</td>
                                    <td>{{ $request->document_type }}</td>
                                    <td>{{ date('M d, Y', strtotime($request->requested_at)) }}</td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($request->status === 'claimed')
                                            <span class="badge badge-info">Claimed</span>
                                        @elseif($request->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="text-center table-actions-col">
                                        @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $documentRequests->firstItem() ?? 0 }} to {{ $documentRequests->lastItem() ?? 0 }} of {{ $documentRequests->total() }} document requests
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($documentRequests->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $documentRequests->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            @for($i = 1; $i <= $documentRequests->lastPage(); $i++)
                                <li class="page-item {{ $i == $documentRequests->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $documentRequests->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            @if($documentRequests->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $documentRequests->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <span style="display:inline-block;width:120px;height:120px;border-radius:50%;background:#f3f4f6;border:4px solid #e5e7eb;display:flex;align-items:center;justify-content:center;">
                            <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="28" cy="28" r="28" fill="#e5e7eb"/>
                                <ellipse cx="28" cy="24" rx="10" ry="12" fill="#f3f4f6"/>
                                <circle cx="23" cy="22" r="2" fill="#bdbdbd"/>
                                <circle cx="33" cy="22" r="2" fill="#bdbdbd"/>
                                <rect x="26" y="28" width="4" height="2" rx="1" fill="#bdbdbd"/>
                            </svg>
                        </span>
                    </div>
                    <h4>No document requests found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'status', 'document_type', 'date_from', 'date_to']))
                            No document requests match your search criteria. <a href="{{ route('admin.documents') }}">Clear all filters</a>
                        @else
                            No document requests have been submitted yet.
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Request Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="section-title">Request Information</div>
                        <div class="info-row">
                            <span class="info-label">Request ID:</span>
                            <span class="info-value" id="modal-request-id"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Document Type:</span>
                            <span class="info-value" id="modal-document-type"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value" id="modal-status"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date Requested:</span>
                            <span class="info-value" id="modal-requested-date"></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="section-title">Resident Information</div>
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value" id="modal-resident-name"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Barangay ID:</span>
                            <span class="info-value" id="modal-barangay-id"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span class="info-value" id="modal-resident-address"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="section-title">Purpose</div>
                        <div id="modal-purpose" class="purpose-box"></div>
                    </div>
                </div>
                <div class="info-row" id="claimed-info-row" style="display:none;">
                    <span class="info-label">Claimed At:</span>
                    <span class="info-value" id="modal-claimed-at"></span>
                </div>
                <div class="info-row" id="claimed-by-row" style="display:none;">
                    <span class="info-label">Claimed By:</span>
                    <span class="info-value" id="modal-claimed-by"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Document Request</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this document request?</p>
                <p class="text-info">
                    <i class="fe fe-info"></i> This will generate the document and notify the resident via email.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprove">
                    <i class="fe fe-check-circle fe-16 mr-2 text-white"></i> Approve Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Document Request</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" placeholder="Please provide a reason for rejecting this request..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Claimed Modal -->
<div class="modal fade" id="markClaimedModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Document as Claimed</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this document as claimed?</p>
                <p class="text-info">
                    <i class="fe fe-info"></i> This indicates that the resident has personally collected the document from the barangay office.
                </p>
                <div class="alert alert-warning">
                    <i class="fe fe-alert-triangle"></i>
                    <strong>Note:</strong> This action should only be performed when the resident has physically collected the document.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmMarkClaimed">
                    <i class="fe fe-check-square fe-16 mr-2 text-white"></i> Mark as Claimed
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl admin-modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <div class="ml-auto">
                    <button type="button" class="btn btn-primary mr-2" id="printDocumentBtn">
                        <i class="fe fe-printer fe-16 mr-2"></i>Print Document
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body admin-modal-body-iframe">
                <iframe id="documentFrame" src="" class="admin-iframe-full"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentRequestId = null;

    // Enhanced smooth scroll functions
    function smoothScrollTo(targetY, duration = 800) {
        const startY = window.pageYOffset;
        const difference = targetY - startY;
        const startTime = performance.now();

        function step() {
            const progress = Math.min((performance.now() - startTime) / duration, 1);
            const ease = easeInOutCubic(progress);
            window.scrollTo(0, startY + difference * ease);
            
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }
        
        requestAnimationFrame(step);
    }

    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
    }

    // Store scroll position before sorting
    function storeScrollPosition() {
        sessionStorage.setItem('tableScrollPosition', window.pageYOffset);
    }
    
    // Enhanced restore scroll position with smooth animation
    function restoreScrollPosition() {
        const scrollPosition = sessionStorage.getItem('tableScrollPosition');
        if (scrollPosition) {
            const targetPosition = parseInt(scrollPosition);
            // Use smooth scroll instead of instant jump
            smoothScrollTo(targetPosition, 600);
            sessionStorage.removeItem('tableScrollPosition');
        }
    }

    // Enhanced smooth scroll to table
    function smoothScrollToTable() {
        const tableElement = document.querySelector('.table-responsive');
        if (tableElement) {
            const targetY = tableElement.getBoundingClientRect().top + window.pageYOffset - 100;
            smoothScrollTo(targetY, 800);
        }
    }
    
    // Enhanced click handlers with visual feedback
    document.querySelectorAll('.table thead a').forEach(link => {
        link.addEventListener('click', function(e) {
            storeScrollPosition();
            // Add visual feedback
            this.classList.add('table-sort-loading');
            setTimeout(() => {
                this.classList.remove('table-sort-loading');
            }, 300);
            // Allow the link to navigate normally
        });
    });
    
    // Enhanced scroll restoration on page load
    setTimeout(() => {
        restoreScrollPosition();
    }, 100);
    
    // Enhanced scroll to table when sorting
    if (window.location.search.includes('sort=')) {
        setTimeout(() => {
            smoothScrollToTable();
        }, 200);
    }

    // View Details
    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const requestId = this.getAttribute('data-id');
            
            fetch(`/admin/documents/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-request-id').textContent = data.id;
                    document.getElementById('modal-document-type').textContent = data.document_type;
                    document.getElementById('modal-status').innerHTML = getStatusBadge(data.status);
                    document.getElementById('modal-requested-date').textContent = new Date(data.requested_at).toLocaleDateString();
                    document.getElementById('modal-resident-name').textContent = data.resident ? 
                        `${data.resident.first_name} ${data.resident.middle_name || ''} ${data.resident.last_name}`.trim() : 'Unknown';
                    document.getElementById('modal-barangay-id').textContent = data.barangay_id;
                    document.getElementById('modal-resident-address').textContent = data.resident ? data.resident.address : 'N/A';
                    document.getElementById('modal-purpose').textContent = data.purpose;
                    
                    if (data.status === 'claimed') {
                        document.getElementById('claimed-info-row').style.display = '';
                        document.getElementById('claimed-by-row').style.display = '';
                        document.getElementById('modal-claimed-at').textContent = data.claimed_at || 'N/A';
                        document.getElementById('modal-claimed-by').textContent = data.claimed_by || 'N/A';
                    } else {
                        document.getElementById('claimed-info-row').style.display = 'none';
                        document.getElementById('claimed-by-row').style.display = 'none';
                    }

                    $('#viewDetailsModal').modal('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading request details');
                });
        });
    });

    // View Document
    document.querySelectorAll('.view-document').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const requestId = this.getAttribute('data-id');
            const documentFrame = document.getElementById('documentFrame');
            const printBtn = document.getElementById('printDocumentBtn');
            
            // Set iframe src to view document
            documentFrame.src = `/admin/documents/${requestId}/view`;
            
            // Set print button to print the iframe content
            printBtn.onclick = function() {
                const iframe = document.getElementById('documentFrame');
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
            
            $('#documentPreviewModal').modal('show');
        });
    });

    // Print Document directly
    document.querySelectorAll('.print-document').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const requestId = this.getAttribute('data-id');
            
            // Create a hidden iframe for printing
            const printFrame = document.createElement('iframe');
            printFrame.style.display = 'none';
            printFrame.src = `/admin/documents/${requestId}/print`;
            
            printFrame.onload = function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
                
                // Remove the iframe after printing
                setTimeout(() => {
                    document.body.removeChild(printFrame);
                }, 1000);
            };
            
            document.body.appendChild(printFrame);
        });
    });

    

    // Approve Request
    document.querySelectorAll('.approve-request').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentRequestId = this.getAttribute('data-id');
            $('#approveModal').modal('show');
        });
    });

    // Confirm Approve
    document.getElementById('confirmApprove').addEventListener('click', function() {
        fetch(`/admin/documents/${currentRequestId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#approveModal').modal('hide');
                location.reload();
            } else {
                alert('Error approving request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error approving request');
        });
    });

    // Reject Request
    document.querySelectorAll('.reject-request').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentRequestId = this.getAttribute('data-id');
            $('#rejectModal').modal('show');
        });
    });

    // Handle reject form submission
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const rejectionReason = document.getElementById('rejection_reason').value;
        
        fetch(`/admin/documents/${currentRequestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rejection_reason: rejectionReason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#rejectModal').modal('hide');
                location.reload();
            } else {
                alert('Error rejecting request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting request');
        });
    });

    // Mark as Claimed
    document.querySelectorAll('.mark-claimed').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentRequestId = this.getAttribute('data-id');
            $('#markClaimedModal').modal('show');
        });
    });

    // Confirm Mark as Claimed
    document.getElementById('confirmMarkClaimed').addEventListener('click', function() {
        fetch(`/admin/documents/${currentRequestId}/mark-claimed`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#markClaimedModal').modal('hide');
                location.reload();
            } else {
                alert('Error marking as claimed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking as claimed');
        });
    });

    function getStatusBadge(status) {
        switch(status) {
            case 'pending':
                return '<span class="badge badge-warning">Pending</span>';
            case 'approved':
                return '<span class="badge badge-success">Approved</span>';
            case 'claimed':
                return '<span class="badge badge-info">Claimed</span>';
            case 'rejected':
                return '<span class="badge badge-danger">Rejected</span>';
            default:
                return '<span class="badge badge-secondary">Unknown</span>';
        }
    }
    
    // Enhanced clear all filters function with smooth scroll
    window.clearAllFilters = function() {
        document.getElementById('filterForm').reset();
        window.location.href = "{{ route('admin.documents') }}";
    };
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // If any filter is active, scroll to the table
    const urlParams = new URLSearchParams(window.location.search);
    const filterKeys = ['search','status','type','date_from','date_to','document_type','resident_gender','resident_civil_status','resident_age_group'];
    let hasFilter = false;
    for (const key of filterKeys) {
        if (urlParams.get(key)) {
            hasFilter = true;
            break;
        }
    }
    if (hasFilter) {
        const table = document.querySelector('.table-responsive, table');
        if (table) {
            table.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});
</script>
@endsection