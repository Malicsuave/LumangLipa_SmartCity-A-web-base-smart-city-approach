@extends('layouts.admin.master')

@push('styles')
@include('admin.components.datatable-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Residents Management</h1>
                <p class="text-muted mb-0">Manage all registered residents in your barangay</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.residents') }}" class="btn btn-info mr-2">
                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                </a>
                <button type="button" class="btn btn-outline-secondary mr-2" onclick="window.location.href='{{ route('admin.residents.archived') }}'">
                    <i class="fas fa-archive mr-2"></i>View Archived
                </button>
                <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>Register New Resident
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Residents</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['male'] }}</h3>
                <p>Male Residents</p>
            </div>
            <div class="icon">
                <i class="fas fa-male"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['female'] }}</h3>
                <p>Female Residents</p>
            </div>
            <div class="icon">
                <i class="fas fa-female"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['with_id'] }}</h3>
                <p>With ID Cards</p>
            </div>
            <div class="icon">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Manage Residents</strong>
            </div>
            <div class="card-body">
                <table id="residentTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Barangay ID</th>
                            <th>Resident Name</th>
                            <th>Type</th>
                            <th>Age/Gender</th>
                            <th>Date Created</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($residents as $resident)
                        <tr>
                            <td><strong>{{ $resident->barangay_id }}</strong></td>
                            <td><strong>{{ $resident->last_name }}, {{ $resident->first_name }}{{ $resident->middle_name ? ' ' . $resident->middle_name : '' }}{{ $resident->suffix ? ' ' . $resident->suffix : '' }}</strong></td>
                            <td>{{ $resident->type_of_resident }}</td>
                            <td>{{ \Carbon\Carbon::parse($resident->birthdate)->age }}<br><small class="text-muted">{{ $resident->sex }}</small></td>
                            <td>{{ $resident->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $resident->created_at->format('h:i A') }}</small></td>
                            <td>{{ $resident->contact_number }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item text-dark" href="{{ route('admin.residents.edit', $resident) }}">
                                            <i class="fas fa-edit mr-2 text-dark"></i>Update Information
                                        </a>
                                        <a class="dropdown-item text-dark" href="{{ route('admin.residents.services', $resident) }}">
                                            <i class="fas fa-clipboard mr-2 text-dark"></i>Services & Documents
                                        </a>
                                        <a class="dropdown-item text-dark" href="javascript:void(0)" onclick="viewResidentDetails({{ $resident->id }})">
                                            <i class="fas fa-eye mr-2 text-dark"></i>View Details
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-dark" href="javascript:void(0)" onclick="handleArchiveClick(event, {{ $resident->id }}, '{{ addslashes($resident->first_name . ' ' . $resident->last_name) }}')">
                                            <i class="fas fa-archive mr-2 text-dark"></i>Archive Resident
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Barangay ID</th>
                            <th>Resident Name</th>
                            <th>Type</th>
                            <th>Age/Gender</th>
                            <th>Date Created</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
                
                <!-- Archive Forms -->
                @foreach($residents as $resident)
                <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resident Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="section-title">Personal Information</div>
                        <div class="info-row">
                            <span class="info-label">Barangay ID:</span>
                            <span class="info-value" id="modal-barangay-id"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Full Name:</span>
                            <span class="info-value" id="modal-resident-name"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Type:</span>
                            <span class="info-value" id="modal-resident-type"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Age/Gender:</span>
                            <span class="info-value" id="modal-age-gender"></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="section-title">Contact Information</div>
                        <div class="info-row">
                            <span class="info-label">Contact Number:</span>
                            <span class="info-value" id="modal-contact"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span class="info-value" id="modal-address"></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date Created:</span>
                            <span class="info-value" id="modal-date-created"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editResident()">Edit Resident</button>
            </div>
        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveResidentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Archive Resident</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive <strong id="residentName"></strong>?</p>
                <p class="text-muted"><small>This action will move the resident to the archived list. The resident can be restored later if needed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmDelete">Archive Resident</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<!-- Common DataTable Helpers -->
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>

<script>
$(function () {
    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable('#residentTable')) {
        $('#residentTable').DataTable().destroy();
    }

    // Initialize DataTable with custom configuration for residents
    const residentTable = DataTableHelpers.initDataTable("#residentTable", {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        pageLength: 10,
        lengthChange: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
    
    // Auto-hide success alerts after 10 seconds (for fallback alerts only)
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 10000);
    
    // Enhanced Font Awesome icon fix for dropdowns
    function fixDropdownIcons() {
        $('.dropdown-item i').each(function() {
            const $icon = $(this);
            const classes = $icon.attr('class') || '';
            
            // Ensure proper Font Awesome styling
            $icon.css({
                'font-family': '"Font Awesome 5 Free"',
                'font-style': 'normal',
                'font-variant': 'normal',
                'text-rendering': 'auto',
                'line-height': '1',
                '-webkit-font-smoothing': 'antialiased',
                'display': 'inline-block',
                'font-weight': classes.includes('fas') ? '900' : '400'
            });
        });
    }
    
    // Fix icons on page load and after DataTable redraws
    fixDropdownIcons();
    
    // Fix icons after DataTable operations
    $('#residentTable').on('draw.dt', function() {
        setTimeout(fixDropdownIcons, 100);
    });
});

// Archive functions
function handleArchiveClick(event, id, name) {
    event.preventDefault();
    event.stopPropagation();
    
    // Close any open dropdowns
    $('.dropdown-toggle').dropdown('hide');
    
    // Small delay to ensure dropdown closes first
    setTimeout(() => {
        confirmArchive(id, name);
    }, 100);
}

function confirmArchive(id, name) {
    document.getElementById('residentName').textContent = name;
    
    // Remove any existing event listeners
    const confirmButton = document.getElementById('confirmDelete');
    const newButton = confirmButton.cloneNode(true);
    confirmButton.parentNode.replaceChild(newButton, confirmButton);
    
    // Add new event listener
    document.getElementById('confirmDelete').onclick = function() {
        console.log('Archive button clicked for ID:', id);
        const form = document.getElementById('delete-form-' + id);
        console.log('Form found:', form);
        
        if (form) {
            // Hide modal first
            $('#archiveResidentModal').modal('hide');
            
            // Add a small delay then submit
            setTimeout(() => {
                form.submit();
            }, 300);
        } else {
            console.error('Form not found: delete-form-' + id);
            toastr.error('Error: Archive form not found. Please refresh the page and try again.', 'Error');
        }
    };
    
    $('#archiveResidentModal').modal('show');
}

// View details function
function viewResidentDetails(id) {
    // Close any open dropdowns
    $('.dropdown-toggle').dropdown('hide');
    
    // AJAX call to get resident details
    $.ajax({
        url: '/admin/residents/' + id + '/details',
        method: 'GET',
        success: function(response) {
            // Populate modal with resident data
            $('#modal-barangay-id').text(response.barangay_id);
            $('#modal-resident-name').text(response.full_name);
            $('#modal-resident-type').text(response.type_of_resident);
            $('#modal-age-gender').text(response.age + ' / ' + response.sex);
            $('#modal-contact').text(response.contact_number);
            $('#modal-address').text(response.address);
            $('#modal-date-created').text(response.date_created);
            
            // Show modal
            $('#viewDetailsModal').modal('show');
        },
        error: function() {
            toastr.error('Error loading resident details. Please try again.', 'Error');
        }
    });
}

function editResident() {
    // Get current resident ID from modal data or implement logic to edit
    $('#viewDetailsModal').modal('hide');
    // Redirect to edit page or implement inline editing
}
</script>
@endpush
