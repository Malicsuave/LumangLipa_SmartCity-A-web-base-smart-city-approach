@extends('layouts.admin.master')



@section('page-title', 'Census Data')
@section('page-subtitle', 'Manage census and household records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Census Data</h1>
                <p class="text-muted mb-0">Manage census and household records</p>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addCensusModal">
                    <i class="fas fa-user-plus mr-2"></i>Add Census Record
                </a>
            </div>
        </div>
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Census Data</strong>
            </div>
            <div class="card-body">
                <!-- Search and Filter removed per request -->

                <!-- Census Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="censusTable" data-export-title="Census Data">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Household Info</th>
                                <th>Date Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($households->count() > 0)
                                @foreach($households as $household)
                                <tr>
                                    <td><strong>{{ $household->primary_name }}</strong></td>
                                    <td>{{ $household->resident->address ?? 'N/A' }}</td>
                                    <td>
                                        <div class="small">
                                            <strong>Gender:</strong> {{ $household->primary_gender }}<br>
                                            <strong>Contact:</strong> {{ $household->primary_phone ?? 'N/A' }}<br>
                                            <strong>Work:</strong> {{ $household->primary_work ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>{{ $household->created_at->format('M d, Y') }}</td>
                                    <td class="text-center table-actions-col">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="editCensusRecord({{ $household->id }})">
                                                    <i class="fas fa-edit mr-2"></i>Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteCensusRecord({{ $household->id }})">
                                                    <i class="fas fa-trash-alt mr-2"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <h5>No census records found</h5>
                                            <p>No census records have been added yet.</p>
                                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addCensusModal">
                                                <i class="fas fa-plus mr-2"></i>Add First Census Record
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Household Info</th>
                                <th>Date Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Pagination (if needed) -->
            </div>
        </div>
    </div>
</div>

<!-- Add Census Record Modal -->
<div class="modal fade" id="addCensusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Census Record</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addCensusForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="resident_id">Select Resident <span class="text-danger">*</span></label>
                                <select class="form-control" id="resident_id" name="resident_id" required>
                                    <option value="">Choose a resident...</option>
                                    @foreach(App\Models\Resident::orderBy('first_name')->get() as $resident)
                                        <option value="{{ $resident->id }}">{{ $resident->first_name }} {{ $resident->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_name">Primary Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="primary_name" name="primary_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_birthday">Primary Birthday <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="primary_birthday" name="primary_birthday" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_gender">Primary Gender <span class="text-danger">*</span></label>
                                <select class="form-control" id="primary_gender" name="primary_gender" required>
                                    <option value="">Select gender...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_phone">Primary Phone</label>
                                <input type="text" class="form-control" id="primary_phone" name="primary_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_work">Primary Work</label>
                                <input type="text" class="form-control" id="primary_work" name="primary_work">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Emergency Contact Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emergency_relationship">Relationship <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="emergency_relationship" name="emergency_relationship" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emergency_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Save Census Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Census Record Modal -->
<div class="modal fade" id="editCensusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Census Record</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCensusForm">
                <input type="hidden" id="edit_household_id" name="household_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_primary_name">Primary Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_primary_name" name="primary_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_primary_birthday">Primary Birthday <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_primary_birthday" name="primary_birthday" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_primary_gender">Primary Gender <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_primary_gender" name="primary_gender" required>
                                    <option value="">Select gender...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_primary_phone">Primary Phone</label>
                                <input type="text" class="form-control" id="edit_primary_phone" name="primary_phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_primary_work">Primary Work</label>
                                <input type="text" class="form-control" id="edit_primary_work" name="primary_work">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Emergency Contact Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_emergency_contact_name" name="emergency_contact_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_emergency_relationship">Relationship <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_emergency_relationship" name="emergency_relationship" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_emergency_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_emergency_phone" name="emergency_phone" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Census Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCensusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Census Record</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this census record?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCensus">
                    <i class="fas fa-trash mr-2"></i>Delete Record
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.DataTableHelpers) {
        DataTableHelpers.initDataTable('#censusTable', {
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            order: [[ 3, 'desc' ]] // Order by date recorded
        });
    }

    // Add Census Form Handler
    $('#addCensusForm').on('submit', function(e) {
        e.preventDefault();
        
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');

        $.ajax({
            url: '{{ route("admin.residents.census-data.store") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addCensusModal').modal('hide');
                    $('#addCensusForm')[0].reset();
                    showSuccess(response.message);
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    showError(response.message || 'Failed to create census record.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error creating census record:', error);
                handleAjaxError(xhr, status, error, 'An error occurred while creating the census record.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Save Census Record');
            }
        });
    });

    // Edit Census Form Handler
    $('#editCensusForm').on('submit', function(e) {
        e.preventDefault();
        
        var householdId = $('#edit_household_id').val();
        if (!householdId) {
            showError('No household ID found.');
            return;
        }

        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');

        $.ajax({
            url: '{{ route("admin.residents.census-data.update", ":id") }}'.replace(':id', householdId),
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editCensusModal').modal('hide');
                    $('#editCensusForm')[0].reset();
                    showSuccess(response.message);
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    showError(response.message || 'Failed to update census record.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating census record:', error);
                handleAjaxError(xhr, status, error, 'An error occurred while updating the census record.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Update Census Record');
            }
        });
    });

    // Delete Census Confirmation Handler
    $('#confirmDeleteCensus').on('click', function() {
        var householdId = window.currentHouseholdId;
        if (!householdId) {
            console.error('No household ID available');
            return;
        }

        // Disable button to prevent double clicks
        $(this).prop('disabled', true).text('Deleting...');

        $.ajax({
            url: '{{ route("admin.residents.census-data.destroy", ":id") }}'.replace(':id', householdId),
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteCensusModal').modal('hide');
                    showSuccess(response.message);
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    showError(response.message || 'Failed to delete census record.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting census record:', error);
                handleAjaxError(xhr, status, error, 'An error occurred while deleting the census record.');
            },
            complete: function() {
                $('#confirmDeleteCensus').prop('disabled', false).html('<i class="fas fa-trash mr-2"></i>Delete Record');
            }
        });
    });
});

// Action functions for DataTable buttons
function editCensusRecord(householdId) {
    console.log('Edit census record:', householdId);
    
    // Show loading state
    $('#edit_household_id').val(householdId);
    $('#edit_primary_name').val('Loading...');
    $('#editCensusModal').modal('show');
    
    // Fetch data via AJAX
    $.ajax({
        url: '{{ route("admin.residents.census-data.edit", ":id") }}'.replace(':id', householdId),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Populate form with data
            $('#edit_primary_name').val(response.primary_name || '');
            $('#edit_primary_birthday').val(response.primary_birthday || '');
            $('#edit_primary_gender').val(response.primary_gender || '');
            $('#edit_primary_phone').val(response.primary_phone || '');
            $('#edit_primary_work').val(response.primary_work || '');
            $('#edit_emergency_contact_name').val(response.emergency_contact_name || '');
            $('#edit_emergency_relationship').val(response.emergency_relationship || '');
            $('#edit_emergency_phone').val(response.emergency_phone || '');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching census record details:', error);
            showError('Failed to load census record details.');
            $('#editCensusModal').modal('hide');
        }
    });
    
    return false;
}

function deleteCensusRecord(householdId) {
    console.log('Delete census record:', householdId);
    window.currentHouseholdId = householdId;
    $('#deleteCensusModal').modal('show');
    return false;
}
</script>
@endpush