@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Census Data</li>
@endsection

@section('page-title', 'Census Data')
@section('page-subtitle', 'Manage household census records and population data')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Census Data</h1>
                <p class="text-muted mb-0">Manage household census records and population data</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.residents.census.step1') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>Add Census Record
                </a>
                <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-users mr-2"></i>
                    All Residents
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_households'] }}</h3>
                        <p>Total Households</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['total_population'] }}</h3>
                        <p>Total Population</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['owned_houses'] }}</h3>
                        <p>Owned Houses</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-house-user"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['rented_houses'] + $stats['apartments'] }}</h3>
                        <p>Rented/Apartments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Census Data</strong>
            </div>
            <div class="card-body">
                <!-- Census Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="censusTable" data-export-title="Census Data">
                        <thead>
                            <tr>
                                <th>Household Head</th>
                                <th>Address</th>
                                <th>Housing Type</th>
                                <th>Total Members</th>
                                <th>Contact Number</th>
                                <th>Date Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($households->count() > 0)
                                @foreach($households as $household)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold" 
                                                     style="width: 40px; height: 40px;">
                                                    {{ substr($household->head_name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $household->head_name }}</strong>
                                                @if($household->members->where('relationship_to_head', 'Head')->first())
                                                    <br><small class="text-muted">{{ $household->members->where('relationship_to_head', 'Head')->first()->gender }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $household->address }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $household->housing_type }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $household->total_members }}</span>
                                        @if($household->members->count() > 0)
                                            <br><small class="text-muted">
                                                {{ $household->members->where('gender', 'Male')->count() }}M / 
                                                {{ $household->members->where('gender', 'Female')->count() }}F
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $household->contact_number ?: 'N/A' }}</td>
                                    <td>
                                        {{ $household->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $household->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center table-actions-col">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" onclick="viewHousehold({{ $household->household_id }})">
                                                    <i class="fas fa-eye mr-2 text-primary"></i>View Details
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="editHousehold({{ $household->household_id }})">
                                                    <i class="fas fa-edit mr-2 text-info"></i>Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteHousehold({{ $household->household_id }})">
                                                    <i class="fas fa-trash-alt mr-2"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-home fa-3x mb-3"></i>
                                            <h5>No census records found</h5>
                                            <p class="mb-3">No census records have been added yet. Start by adding your first household census record.</p>
                                            <a href="{{ route('admin.residents.census.step1') }}" class="btn btn-primary">
                                                <i class="fas fa-plus mr-2"></i>Add First Census Record
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Household Head</th>
                                <th>Address</th>
                                <th>Housing Type</th>
                                <th>Total Members</th>
                                <th>Contact Number</th>
                                <th>Date Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Household Details Modal -->
<div class="modal fade" id="viewHouseholdModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Household Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="householdDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this household census record? This action cannot be undone and will remove all household members as well.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script>
let currentHouseholdId = null;

// Initialize DataTable with export functionality
$(document).ready(function() {
    if (window.DataTableHelpers) {
        DataTableHelpers.initDataTable('#censusTable', {
            pageLength: 25,
            order: [[5, 'desc']], // Sort by Date Recorded descending
            columnDefs: [
                { orderable: false, targets: [6] } // Disable ordering on Actions column
            ],
            language: {
                emptyTable: "No census records available"
            },
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
    }
});

// View household details
function viewHousehold(householdId) {
    $('#householdDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#viewHouseholdModal').modal('show');
    
    // For now, show basic info (you can implement detailed view later)
    $('#householdDetailsContent').html('<div class="alert alert-info">Household details view will be implemented soon.</div>');
}

// Edit household
function editHousehold(householdId) {
    // For now, show message (you can implement editing later)
    alert('Household editing feature will be implemented soon. Use the multi-step census registration for now.');
}

// Delete household
function deleteHousehold(householdId) {
    currentHouseholdId = householdId;
    $('#deleteConfirmModal').modal('show');
}

// Confirm delete
$('#confirmDeleteBtn').on('click', function() {
    if (!currentHouseholdId) return;
    
    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
    
    $.ajax({
        url: `/admin/residents/census-data/${currentHouseholdId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#deleteConfirmModal').modal('hide');
            showSuccess('Household census record deleted successfully.');
            // Reload page to update table
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr, status, error) {
            console.error('Error deleting household:', error);
            showError('Failed to delete household census record.');
            $('#confirmDeleteBtn').prop('disabled', false).html('Delete');
        }
    });
});

// Helper functions for notifications
function showSuccess(message) {
    if (typeof toastr !== 'undefined') {
        toastr.success(message);
    } else {
        alert(message);
    }
}

function showError(message) {
    if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else {
        alert(message);
    }
}
</script>
@endpush
