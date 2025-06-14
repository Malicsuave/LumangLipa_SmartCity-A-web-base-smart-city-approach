@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">ID Card Management</li>
@endsection

@section('page-title', 'ID Card Management')
@section('page-subtitle', 'Manage resident ID card issuance and renewals')

@section('content')
<!-- Filter Panel -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="mb-0"><i class="fe fe-filter fe-16 mr-2"></i>Filter Options</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.residents.id.pending') }}" method="GET" class="row">
                    <div class="col-md-3 mb-3">
                        <label for="status_filter">ID Status Filter</label>
                        <select name="status_filter" id="status_filter" class="form-control">
                            <option value="default" {{ request('status_filter', 'default') == 'default' ? 'selected' : '' }}>Default View (Filtered by Status)</option>
                            <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>Show All Residents</option>
                            <option value="issued" {{ request('status_filter') == 'issued' ? 'selected' : '' }}>Only Issued IDs</option>
                            <option value="not_issued" {{ request('status_filter') == 'not_issued' ? 'selected' : '' }}>Only Non-Issued IDs</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="expiry_filter">Expiry Time Frame</label>
                        <select name="expiry_filter" id="main_expiry_filter" class="form-control">
                            <option value="default" {{ request('expiry_filter', 'default') == 'default' ? 'selected' : '' }}>Default (3 months)</option>
                            <option value="1month" {{ request('expiry_filter') == '1month' ? 'selected' : '' }}>1 Month</option>
                            <option value="6months" {{ request('expiry_filter') == '6months' ? 'selected' : '' }}>6 Months</option>
                            <option value="1year" {{ request('expiry_filter') == '1year' ? 'selected' : '' }}>1 Year</option>
                            <option value="custom" {{ request('expiry_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 custom-date-range" style="{{ request('expiry_filter') == 'custom' ? '' : 'display: none;' }}">
                        <label for="expiry_start_date">Start Date</label>
                        <input type="date" name="expiry_start_date" id="expiry_start_date" class="form-control" value="{{ request('expiry_start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3 custom-date-range" style="{{ request('expiry_filter') == 'custom' ? '' : 'display: none;' }}">
                        <label for="expiry_end_date">End Date</label>
                        <input type="date" name="expiry_end_date" id="expiry_end_date" class="form-control" value="{{ request('expiry_end_date') }}">
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fe fe-filter fe-16 mr-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-credit-card fe-16 mr-2"></i>Pending ID Issuance</h4>
                        <p class="text-muted mb-0">
                            @if(request('status_filter') == 'all')
                                Showing all residents
                            @else
                                Residents ready for ID card issuance
                            @endif
                        </p>
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
                        <p class="text-muted">All residents have their ID cards issued or are missing required photos.</p>
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
                <div class="p-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Expiring IDs</h2>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Existing Expiry Filter Dropdown -->
                            <select name="expiry_filter" id="expiry_filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                                <option value="default" {{ request('expiry_filter', 'default') == 'default' ? 'selected' : '' }}>Expiring in 3 Months</option>
                                <option value="1month" {{ request('expiry_filter') == '1month' ? 'selected' : '' }}>Expiring in 1 Month</option>
                                <option value="6months" {{ request('expiry_filter') == '6months' ? 'selected' : '' }}>Expiring in 6 Months</option>
                                <option value="1year" {{ request('expiry_filter') == '1year' ? 'selected' : '' }}>Expiring in 1 Year</option>
                                <option value="custom" {{ (request('expiry_start_date') && request('expiry_end_date')) ? 'selected' : '' }}>Custom Range</option>
                            </select>
                            
                            <!-- New: Custom Date Range Filters -->
                            <div id="date-range-filters" class="flex items-center space-x-2" style="{{ (request('expiry_start_date') && request('expiry_end_date')) ? '' : 'display: none;' }}">
                                <input 
                                    type="date" 
                                    name="expiry_start_date" 
                                    id="expiry_start_date" 
                                    value="{{ request('expiry_start_date') }}" 
                                    class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <span class="text-gray-500">to</span>
                                <input 
                                    type="date" 
                                    name="expiry_end_date" 
                                    id="expiry_end_date" 
                                    value="{{ request('expiry_end_date') }}" 
                                    class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <button type="submit" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
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
                                                <span class="text-warning">{{ $resident->id_expires_at ? $resident->id_expires_at->format('M d, Y') : 'N/A' }}</span>
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
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#pendingIssuanceTable, #pendingRenewalTable, #expiringSoonTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true
        });

        // Handle expiry filter change in the main filter panel
        $('#main_expiry_filter').on('change', function() {
            if ($(this).val() === 'custom') {
                $('.custom-date-range').show();
            } else {
                $('.custom-date-range').hide();
            }
        });

        // Handle expiry filter change in the Expiring Soon section
        $('#expiry_filter').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#date-range-filters').show();
            } else {
                $('#date-range-filters').hide();
            }
        });
    });
</script>
@endsection