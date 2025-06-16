@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">ID Card Management</li>
@endsection

@section('page-title', 'ID Card Management')
@section('page-subtitle', 'Manage resident ID card issuance and renewals')

@section('styles')
<style>
    /* Fix for dropdown menus in tables with few rows */
    .dropdown-menu {
        position: absolute;
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
    }
    
    /* Ensure table doesn't cause horizontal scrolling with dropdowns */
    .table-responsive {
        overflow-x: visible;
        overflow-y: visible;
    }
    
    /* When only one row exists, maintain dropdown menu position */
    .table tr:only-child .dropdown-menu {
        right: 0;
        left: auto;
        transform: none !important;
        top: 100% !important;
        position: fixed;
    }
    
    /* For single row tables, adjust dropdown position to prevent scrolling */
    .table-responsive:has(tr:only-child) {
        overflow: visible;
    }
    
    /* Ensure dropdown is always on top of other elements */
    .dropdown-menu.show {
        z-index: 1050;
    }
    
    /* Filter badge styles */
    .filter-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        cursor: pointer;
    }
    
    /* Filter section styles */
    .filter-section {
        border-left: 3px solid #1b68ff;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-credit-card fe-16 mr-2"></i>Pending ID Issuance</h4>
                        <p class="text-muted mb-0">Residents ready for ID card issuance</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.residents.id.bulk-upload') }}" class="btn btn-primary mr-2">
                            <i class="fe fe-upload-cloud fe-16 mr-2"></i>Bulk Photo Upload
                        </a>
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-users fe-16 mr-2"></i>All Residents
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <form action="{{ route('admin.residents.id.pending') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search by name, ID, phone number..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary w-100" data-toggle="collapse" data-target="#filterSection" aria-expanded="false">
                                <i class="fe fe-filter fe-16 mr-1"></i>Filter Options
                            </button>
                        </div>
                        <div class="col-md-3">
                            @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo', 'id_status']))
                                <a href="{{ route('admin.residents.id.pending') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['type', 'gender', 'age_group', 'has_photo', 'id_status']) ? 'show' : '' }}" id="filterSection">
                        <div class="card filter-section mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter residents by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Resident Type</label>
                                        <select name="type" class="form-control form-control-sm">
                                            <option value="">All Types</option>
                                            <option value="Non-migrant" {{ request('type') == 'Non-migrant' ? 'selected' : '' }}>Non-migrant</option>
                                            <option value="Migrant" {{ request('type') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                            <option value="Transient" {{ request('type') == 'Transient' ? 'selected' : '' }}>Transient</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-control form-control-sm">
                                            <option value="">All Genders</option>
                                            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Age Group</label>
                                        <select name="age_group" class="form-control form-control-sm">
                                            <option value="">All Ages</option>
                                            <option value="0-17" {{ request('age_group') == '0-17' ? 'selected' : '' }}>0-17 (Minor)</option>
                                            <option value="18-59" {{ request('age_group') == '18-59' ? 'selected' : '' }}>18-59 (Adult)</option>
                                            <option value="60+" {{ request('age_group') == '60+' ? 'selected' : '' }}>60+ (Senior)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Photo Status</label>
                                        <select name="has_photo" class="form-control form-control-sm">
                                            <option value="">All Photo Status</option>
                                            <option value="yes" {{ request('has_photo') == 'yes' ? 'selected' : '' }}>With Photo</option>
                                            <option value="no" {{ request('has_photo') == 'no' ? 'selected' : '' }}>No Photo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">ID Status</label>
                                        <select name="id_status" class="form-control form-control-sm">
                                            <option value="">All Statuses</option>
                                            <option value="issued" {{ request('id_status') == 'issued' ? 'selected' : '' }}>Issued</option>
                                            <option value="not_issued" {{ request('id_status') == 'not_issued' ? 'selected' : '' }}>Not Issued</option>
                                            <option value="ready" {{ request('id_status') == 'ready' ? 'selected' : '' }}>Ready for Issuance</option>
                                            <option value="valid" {{ request('id_status') == 'valid' ? 'selected' : '' }}>Valid</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Filter Actions -->
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary mr-3" onclick="clearAllFilters()">
                                            <i class="fe fe-x fe-16 mr-1"></i>Clear Filters
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo', 'id_status']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    @if(isset($pendingIssuance))
                                    <span class="badge badge-info ml-2">{{ $pendingIssuance->total() }} results found</span>
                                    @endif
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none filter-badge">
                                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('type'))
                                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="badge badge-primary mr-1 text-decoration-none filter-badge">
                                        Type: {{ request('type') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('gender'))
                                    <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="badge badge-success mr-1 text-decoration-none filter-badge">
                                        Gender: {{ request('gender') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('age_group'))
                                    <a href="{{ request()->fullUrlWithQuery(['age_group' => null]) }}" class="badge badge-warning mr-1 text-decoration-none filter-badge">
                                        Age Group: {{ request('age_group') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('has_photo'))
                                    <a href="{{ request()->fullUrlWithQuery(['has_photo' => null]) }}" class="badge badge-info mr-1 text-decoration-none filter-badge">
                                        Photo: {{ request('has_photo') == 'yes' ? 'With Photo' : 'No Photo' }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('id_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['id_status' => null]) }}" class="badge badge-secondary mr-1 text-decoration-none filter-badge">
                                        ID Status: {{ request('id_status') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fe fe-alert-circle fe-16 mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($pendingIssuance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped" id="pendingIssuanceTable">
                            <thead>
                                <tr>
                                    <th>Barangay ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Age/Gender</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingIssuance as $resident)
                                    <tr>
                                        <td><strong>{{ $resident->barangay_id }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    @if($resident->photo)
                                                        <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                                    @else
                                                        <div class="avatar-letter rounded-circle bg-warning">{{ substr($resident->first_name, 0, 1) }}</div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                                    @if($resident->middle_name)
                                                        {{ substr($resident->middle_name, 0, 1) }}.
                                                    @endif
                                                    {{ $resident->suffix }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $resident->type_of_resident }}</span>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                            <br><small class="text-muted">{{ $resident->sex }}</small>
                                        </td>
                                        <td>
                                            @if($resident->photo)
                                                @if($resident->id_status === 'issued')
                                                    <span class="badge badge-soft-success">Issued</span>
                                                @else
                                                    <span class="badge badge-soft-primary">Ready</span>
                                                @endif
                                            @else
                                                <span class="badge badge-soft-warning">No Photo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-credit-card fe-16 mr-2 text-primary"></i>Manage ID
                                                    </a>
                                                    @if(!$resident->photo)
                                                        <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="dropdown-item">
                                                            <i class="fe fe-camera fe-16 mr-2 text-warning"></i>Add Photo
                                                        </a>
                                                    @endif
                                                    @if($resident->photo && $resident->id_status !== 'issued')
                                                        <form action="{{ route('admin.residents.id.issue', $resident->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to issue an ID for this resident?')">
                                                                <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Issue ID
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('admin.residents.id.preview', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-image fe-16 mr-2 text-info"></i>Preview ID
                                                    </a>
                                                    @if($resident->id_status === 'issued')
                                                        <a href="{{ route('admin.residents.id.download', $resident->id) }}" class="dropdown-item">
                                                            <i class="fe fe-download fe-16 mr-2 text-success"></i>Download ID
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Add pagination links below the table -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pendingIssuance->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="{{ asset('images/empty.svg') }}" alt="No pending IDs" class="img-fluid mb-3" width="200">
                        <h4>No pending ID issuance</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo', 'id_status']))
                                No residents match your search criteria. <a href="{{ route('admin.residents.id.pending') }}">Clear all filters</a>
                            @else
                                All residents have their ID cards issued or are missing required photos.
                            @endif
                        </p>
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-primary">
                            <i class="fe fe-users fe-16 mr-2"></i>View All Residents
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-refresh-cw fe-16 mr-2"></i>IDs Pending Renewal</h4>
                        <p class="text-muted mb-0">Residents with IDs marked for renewal</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($pendingRenewal->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped" id="pendingRenewalTable">
                            <thead>
                                <tr>
                                    <th>Barangay ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Age/Gender</th>
                                    <th>Issued Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRenewal as $resident)
                                    <tr>
                                        <td><strong>{{ $resident->barangay_id }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    @if($resident->photo)
                                                        <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                                    @else
                                                        <div class="avatar-letter rounded-circle bg-warning">{{ substr($resident->first_name, 0, 1) }}</div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                                    @if($resident->middle_name)
                                                        {{ substr($resident->middle_name, 0, 1) }}.
                                                    @endif
                                                    {{ $resident->suffix }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $resident->type_of_resident }}</span>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                            <br><small class="text-muted">{{ $resident->sex }}</small>
                                        </td>
                                        <td>{{ $resident->id_issued_at ? $resident->id_issued_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-credit-card fe-16 mr-2 text-primary"></i>Manage ID
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('admin.residents.id.issue', $resident->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to issue a new ID for this resident?')">
                                                            <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Issue New ID (Renew)
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.residents.id.preview', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-image fe-16 mr-2 text-info"></i>Preview ID
                                                    </a>
                                                    <a href="{{ route('admin.residents.id.download', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-download fe-16 mr-2 text-success"></i>Download ID
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('admin.residents.id.revoke', $resident->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to revoke this resident\'s ID? This action cannot be undone.')">
                                                            <i class="fe fe-x-circle fe-16 mr-2"></i>Revoke ID
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.residents.id.remove-renewal', $resident->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Remove this resident from the renewal queue?')">
                                                            <i class="fe fe-minus-circle fe-16 mr-2 text-warning"></i>Remove from Renewal Queue
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Add pagination links below the table -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pendingRenewal->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="{{ asset('images/empty.svg') }}" alt="No pending renewals" class="img-fluid mb-3" width="200">
                        <h4>No pending renewals</h4>
                        <p class="text-muted">No resident IDs are currently marked for renewal.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-alert-triangle fe-16 mr-2"></i>Expiring Soon</h4>
                        <p class="text-muted mb-0">IDs expiring within 3 months</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($expiringSoon->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped" id="expiringSoonTable">
                            <thead>
                                <tr>
                                    <th>Barangay ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Age/Gender</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiringSoon as $resident)
                                    <tr>
                                        <td><strong>{{ $resident->barangay_id }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    @if($resident->photo)
                                                        <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                                    @else
                                                        <div class="avatar-letter rounded-circle bg-warning">{{ substr($resident->first_name, 0, 1) }}</div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                                    @if($resident->middle_name)
                                                        {{ substr($resident->middle_name, 0, 1) }}.
                                                    @endif
                                                    {{ $resident->suffix }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $resident->type_of_resident }}</span>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                            <br><small class="text-muted">{{ $resident->sex }}</small>
                                        </td>
                                        <td>
                                            <span class="text-danger font-weight-bold">{{ $resident->id_expires_at ? $resident->id_expires_at->format('M d, Y') : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-credit-card fe-16 mr-2 text-primary"></i>Manage ID
                                                    </a>
                                                    <a href="{{ route('admin.residents.id.mark-renewal', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-refresh-cw fe-16 mr-2 text-warning"></i>Mark for Renewal
                                                    </a>
                                                    <a href="{{ route('admin.residents.id.preview', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-image fe-16 mr-2 text-info"></i>Preview ID
                                                    </a>
                                                    <a href="{{ route('admin.residents.id.download', $resident->id) }}" class="dropdown-item">
                                                        <i class="fe fe-download fe-16 mr-2 text-success"></i>Download ID
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Add pagination links below the table -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $expiringSoon->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="{{ asset('images/empty.svg') }}" alt="No expiring IDs" class="img-fluid mb-3" width="200">
                        <h4>No IDs expiring soon</h4>
                        <p class="text-muted">No resident IDs are expiring within the next 3 months.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function clearAllFilters() {
        document.getElementById('filterForm').reset();
        window.location.href = "{{ route('admin.residents.id.pending') }}";
    }

    $(document).ready(function() {
        // Initialize DataTables with custom options
        var pendingIssuanceTable = $('#pendingIssuanceTable').DataTable({
            "paging": false, // Disable DataTables pagination as we're using Laravel's
            "searching": false, // Disable default search as we already have our own
            "ordering": true,
            "info": false, // Hide "Showing X to Y of Z entries" text
            "responsive": true,
            "dom": '<"row"<"col-sm-12"tr>>', // Only show the table without DataTables' controls
            "language": {
                "emptyTable": "No pending IDs found"
            }
        });
        
        $('#pendingRenewalTable, #expiringSoonTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "responsive": true,
            "dom": '<"row"<"col-sm-12"tr>>', 
            "language": {
                "emptyTable": "No records found"
            }
        });
    });
</script>
@endsection