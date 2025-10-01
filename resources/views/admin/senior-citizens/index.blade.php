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
                <h1 class="h3 mb-0 text-gray-800">Senior Citizens Management</h1>
                <p class="text-muted mb-0">Manage senior citizen residents (60 years and above)</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.senior-citizens') }}" class="btn btn-info mr-2">
                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                </a>
                <button type="button" class="btn btn-outline-secondary mr-2" onclick="window.location.href='{{ route('admin.senior-citizens.archived') }}'">
                    <i class="fas fa-archive mr-2"></i>View Archived
                </button>
                <a href="{{ route('admin.senior-citizens.register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>Register Senior Citizen
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Senior Citizens</strong>
            </div>
            <div class="card-body">
                <table id="seniorCitizenTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Senior ID</th>
                            <th>Senior Name</th>
                            <th>Type</th>
                            <th>Age/Gender</th>
                            <th>Date Created</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seniorCitizens as $senior)
                        <tr>
                            <td><strong>{{ $senior->senior_id_number ?: 'Not Assigned' }}</strong></td>
                            <td><strong>{{ $senior->last_name }}, {{ $senior->first_name }}{{ $senior->middle_name ? ' ' . $senior->middle_name : '' }}{{ $senior->suffix ? ' ' . $senior->suffix : '' }}</strong></td>
                            <td>Senior Citizen</td>
                            <td>{{ \Carbon\Carbon::parse($senior->birthdate)->age }}<br><small class="text-muted">{{ $senior->sex }}</small></td>
                            <td>{{ $senior->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $senior->created_at->format('h:i A') }}</small></td>
                            <td>{{ $senior->contact_number }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ route('admin.senior-citizens.edit', $senior->id) }}">
                                            <i class="fas fa-edit mr-2"></i>Update Information
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.senior-citizens.services', $senior) }}">
                                            <i class="fas fa-file-alt mr-2"></i>Service & Documents
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="viewSeniorDetails({{ $senior->id }})">
                                            <i class="fas fa-eye mr-2"></i>View Details
                                        </a>
                                        @if($senior->senior_id_status !== 'issued')
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id-management', $senior) }}">
                                                <i class="fas fa-id-card mr-2"></i>Issue Senior ID
                                            </a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="handleArchiveClick(event, {{ $senior->id }}, '{{ addslashes($senior->first_name . ' ' . $senior->last_name) }}')">
                                            <i class="fas fa-archive mr-2"></i>Archive Resident
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveSeniorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Archive Senior Citizen</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive <strong id="seniorName"></strong>?</p>
                <p class="text-muted"><small>This action will move the senior citizen to the archived list. The senior citizen can be restored later if needed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmArchive">Archive Senior Citizen</button>
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
    // Initialize DataTable with custom configuration for senior citizens
    const seniorCitizenTable = DataTableHelpers.initDataTable("#seniorCitizenTable", {
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
});

function viewSeniorDetails(seniorId) {
    // You can implement this function to show senior citizen details in a modal
    console.log('View senior details for ID: ' + seniorId);
}

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
    document.getElementById('seniorName').textContent = name;
    document.getElementById('confirmArchive').onclick = function() {
        // Create and submit form
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/senior-citizens/' + id + '/archive';
        
        // Add CSRF token
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    };
    $('#archiveSeniorModal').modal('show');
}
</script>
@endpush