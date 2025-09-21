@extends('layouts.admin.master')

@push('styles')
@include('admin.components.datatable-styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<!-- Debug Information (only show if session data is missing) -->
@if(session('error'))
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
@endif

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
                                        <a class="dropdown-item" href="{{ route('admin.residents.edit', $resident) }}">
                                            <i class="fas fa-edit mr-2"></i>Update Information
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.residents.services', $resident) }}">
                                            <i class="fas fa-clipboard mr-2"></i>Services & Documents
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="viewResidentDetails({{ $resident->id }})">
                                            <i class="fas fa-eye mr-2"></i>View Details
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-warning" href="javascript:void(0)" onclick="handleArchiveClick(event, {{ $resident->id }}, '{{ addslashes($resident->first_name . ' ' . $resident->last_name) }}')">
                                            <i class="fas fa-archive mr-2"></i>Archive Resident
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Delete Form -->
                        <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
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
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(function () {
    // Show success message with SweetAlert2 for registration completion
    @if(session('success'))
        @php
            $successMessage = session('success');
            $hasBarangayId = strpos($successMessage, 'Barangay ID:') !== false;
        @endphp
        
        @if($hasBarangayId)
            @php
                // Extract the Barangay ID for highlighting
                preg_match('/Barangay ID: ([A-Z0-9-]+)/', $successMessage, $matches);
                $barangayId = $matches[1] ?? '';
                $message = str_replace("Barangay ID: {$barangayId}", '', $successMessage);
            @endphp
            
            Swal.fire({
                icon: 'success',
                title: 'Registration Completed!',
                html: `
                    <div style="text-align: center;">
                        <p style="font-size: 16px; margin-bottom: 20px;">{{ trim($message) }}</p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                            <strong style="font-size: 18px; color: #28a745;">Barangay ID: {{ $barangayId }}</strong>
                        </div>
                        <p style="font-size: 14px; color: #6c757d; margin-top: 15px;">
                            <i class="fas fa-info-circle"></i> This ID can be used for barangay services and ID card generation.
                        </p>
                    </div>
                `,
                confirmButtonText: 'View Resident Records',
                confirmButtonColor: '#28a745',
                allowOutsideClick: false,
                customClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                timer: null
            }).then((result) => {
                if (result.isConfirmed) {
                    // Refresh the page to show the updated table with the new resident
                    window.location.reload();
                }
            });
        @else
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ $successMessage }}',
                confirmButtonText: 'Great!',
                confirmButtonColor: '#28a745',
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                }
            });
        @endif
    @endif
    
    // Show error message with SweetAlert2
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    @endif

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
    document.getElementById('confirmDelete').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
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
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error loading resident details. Please try again.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
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
