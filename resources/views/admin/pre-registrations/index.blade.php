@extends('layouts.admin.master')

@section('title', 'Pre-Registration Management')

@push('styles')
@include('admin.components.datatable-styles')
<link rel="stylesheet" href="{{ asset('css/admin-common.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Pre-Registration Management</h1>
                <p class="text-muted mb-0">Review and approve resident pre-registration applications</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i>View Public Form
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
                <p>Total Applications</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending'] }}</h3>
                <p>Pending Review</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['approved'] }}</h3>
                <p>Approved</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['rejected'] }}</h3>
                <p>Rejected</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Pre-Registration Applications</strong>
            </div>
            <div class="card-body">
                @if($preRegistrations->count() > 0)
                    <table id="preRegistrationsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Registration ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Age</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preRegistrations as $registration)
                                @php
                                    $dropdownItems = [];
                                    $dropdownItems[] = [
                                        'label' => 'View Details',
                                        'icon' => 'fas fa-eye',
                                        'class' => '',
                                        'href' => route('admin.pre-registrations.show', $registration),
                                    ];
                                    if ($registration->status === 'pending') {
                                        $dropdownItems[] = ['divider' => true];
                                        if ($registration->is_senior ?? false) {
                                            $dropdownItems[] = [
                                                'label' => 'Approve',
                                                'icon' => 'fas fa-check',
                                                'class' => '',
                                                'attrs' => 'onclick="approveSeniorRegistration(' . $registration->id . ')"',
                                            ];
                                            $dropdownItems[] = [
                                                'label' => 'Reject',
                                                'icon' => 'fas fa-times',
                                                'class' => '',
                                                'attrs' => 'onclick="rejectSeniorRegistration(' . $registration->id . ')"',
                                            ];
                                        } else {
                                            $dropdownItems[] = [
                                                'label' => 'Approve',
                                                'icon' => 'fas fa-check',
                                                'class' => '',
                                                'attrs' => 'onclick="approveRegistration(' . $registration->id . ')"',
                                            ];
                                            $dropdownItems[] = [
                                                'label' => 'Reject',
                                                'icon' => 'fas fa-times',
                                                'class' => '',
                                                'attrs' => 'onclick="rejectRegistration(' . $registration->id . ')"',
                                            ];
                                        }
                                    }
                                    $dropdownItems[] = ['divider' => true];
                                    if ($registration->is_senior ?? false) {
                                        $dropdownItems[] = [
                                            'label' => 'Delete',
                                            'icon' => 'fas fa-trash',
                                            'class' => '',
                                            'attrs' => 'onclick="deleteSeniorRegistration(' . $registration->id . ')"',
                                        ];
                                    } else {
                                        $dropdownItems[] = [
                                            'label' => 'Delete',
                                            'icon' => 'fas fa-trash',
                                            'class' => '',
                                            'attrs' => 'onclick="deleteRegistration(' . $registration->id . ')"',
                                        ];
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $registration->registration_id ?? 'PRE-' . $registration->created_at->format('Y-m') . '-' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $registration->full_name }}</strong>
                                        @if($registration->is_senior ?? false)
                                            <br><small class="badge badge-warning">Senior Citizen</small>
                                        @else
                                            <br><small class="badge badge-info">Regular Resident</small>
                                        @endif
                                    </td>
                                    <td>{{ $registration->email_address }}</td>
                                    <td>{{ $registration->contact_number }}</td>
                                    <td>{{ $registration->age }}</td>
                                    <td>{{ $registration->type_of_resident }}</td>
                                    <td>
                                        @if($registration->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($registration->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $registration->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $registration->created_at->format('h:i A') }}</small></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($registration->is_senior ?? false)
                                                    <a class="dropdown-item" href="{{ route('admin.pre-registrations.show-senior', $registration) }}">
                                                        <i class="fas fa-eye mr-2"></i>View Details
                                                    </a>
                                                @else
                                                    <a class="dropdown-item" href="{{ route('admin.pre-registrations.show', $registration) }}">
                                                        <i class="fas fa-eye mr-2"></i>View Details
                                                    </a>
                                                @endif
                                                @if($registration->status === 'pending')
                                                    @if($registration->is_senior ?? false)
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="approveSeniorRegistration({{ $registration->id }})">
                                                            <i class="fas fa-check mr-2"></i>Approve
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="rejectSeniorRegistration({{ $registration->id }})">
                                                            <i class="fas fa-times mr-2"></i>Reject
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="approveRegistration({{ $registration->id }})">
                                                            <i class="fas fa-check mr-2"></i>Approve
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="rejectRegistration({{ $registration->id }})">
                                                            <i class="fas fa-times mr-2"></i>Reject
                                                        </a>
                                                    @endif
                                                @endif
                                                <div class="dropdown-divider"></div>
                                                @if($registration->is_senior ?? false)
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="deleteSeniorRegistration({{ $registration->id }})">
                                                        <i class="fas fa-trash mr-2"></i>Delete
                                                    </a>
                                                @else
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="deleteRegistration({{ $registration->id }})">
                                                        <i class="fas fa-trash mr-2"></i>Delete
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Registration ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Age</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
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
                    <h4>No pre-registrations found</h4>
                    <p class="text-muted">
                        No applications have been submitted yet.
                    </p>
                    <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-external-link-alt mr-2"></i>View Public Registration Form
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this registration?</p>
                <p class="text-info">
                    <i class="fas fa-info-circle"></i> This will create a resident record and send a digital ID to the applicant's email.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" class="admin-inline-form">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle mr-2"></i> Approve
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this registration:</p>
                    <textarea class="form-control" name="rejection_reason" rows="3" 
                              placeholder="Enter reason for rejection..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this registration?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="admin-inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>
<script>
$(function () {
    // Initialize DataTable for pre-registrations table - same config as Documents page
    const preRegistrationsTable = DataTableHelpers.initDataTable("#preRegistrationsTable", {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 7, "desc" ]],  // Order by Submitted date (descending) - column index 7
        pageLength: 10,
        lengthChange: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        paging: true,
        info: true,
        searching: true,
        columnDefs: [
            { "orderable": false, "targets": -1 },
            { "responsivePriority": 1, "targets": 0 },
            { "responsivePriority": 2, "targets": 1 },
            { "responsivePriority": 3, "targets": 6 },
            { "responsivePriority": 4, "targets": 2 },
            { "responsivePriority": 5, "targets": 5 },
            { "responsivePriority": 10, "targets": -1 }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6">>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });

    // Helper functions for status badge formatting
    window.getStatusBadge = function(status) {
        const statuses = {
            'pending': '<span class="badge badge-warning">Pending</span>',
            'approved': '<span class="badge badge-success">Approved</span>',
            'rejected': '<span class="badge badge-danger">Rejected</span>'
        };
        return statuses[status] || '<span class="badge badge-secondary">Unknown</span>';
    }

    window.formatDate = function(dateString) {
        try {
            const d = new Date(dateString);
            return isNaN(d.getTime()) ? (dateString || 'N/A') : d.toLocaleString();
        } catch (e) {
            return dateString || 'N/A';
        }
    };
});

// Define functions in global scope
window.approveRegistration = function(id) {
    console.log('Approve function called for ID:', id);
    const form = document.getElementById('approveForm');
    if (form) {
        const newAction = `/admin/pre-registrations/${id}/approve`;
        form.action = newAction;
        
        // Test form submission
        form.addEventListener('submit', function(e) {
            console.log('Form is submitting to:', this.action);
        });
        
        $('#approveModal').modal('show');
    } else {
        console.error('Approve form not found');
    }
};

window.rejectRegistration = function(id) {
    console.log('Reject function called for ID:', id);
    const form = document.getElementById('rejectForm');
    if (form) {
        form.action = `/admin/pre-registrations/${id}/reject`;
        $('#rejectModal').modal('show');
    } else {
        console.error('Reject form not found');
    }
};

window.deleteRegistration = function(id) {
    console.log('Delete function called for ID:', id);
    const form = document.getElementById('deleteForm');
    if (form) {
        form.action = `/admin/pre-registrations/${id}`;
        $('#deleteModal').modal('show');
    } else {
        console.error('Delete form not found');
    }
};

// Senior registration functions
window.approveSeniorRegistration = function(id) {
    console.log('Approve senior function called for ID:', id);
    const form = document.getElementById('approveForm');
    if (form) {
        const newAction = `/admin/pre-registrations/senior/${id}/approve`;
        form.action = newAction;
        $('#approveModal').modal('show');
    } else {
        console.error('Approve form not found');
    }
};

window.rejectSeniorRegistration = function(id) {
    console.log('Reject senior function called for ID:', id);
    const form = document.getElementById('rejectForm');
    if (form) {
        form.action = `/admin/pre-registrations/senior/${id}/reject`;
        $('#rejectModal').modal('show');
    } else {
        console.error('Reject form not found');
    }
};

window.deleteSeniorRegistration = function(id) {
    console.log('Delete senior function called for ID:', id);
    const form = document.getElementById('deleteForm');
    if (form) {
        form.action = `/admin/pre-registrations/senior/${id}`;
        $('#deleteModal').modal('show');
    } else {
        console.error('Delete form not found');
    }
};

// Initialize when document is ready
$(document).ready(function() {
    console.log('Pre-registration page initialized');
    
    // Enhanced form submission handlers
    $('#approveForm').on('submit', function(e) {
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Form action is not set correctly. Please try clicking approve again.');
            return false;
        }
        
        if (!this.action.includes('/admin/pre-registrations/') || !this.action.includes('/approve')) {
            e.preventDefault();
            alert('Error: Form action is not set correctly. Please try clicking approve again.');
            return false;
        }
    });
    
    $('#rejectForm').on('submit', function(e) {
        const reason = $(this).find('textarea[name="rejection_reason"]').val();
        if (!reason.trim()) {
            e.preventDefault();
            alert('Please provide a reason for rejection.');
            return false;
        }
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Invalid form action. Please try again.');
            return false;
        }
    });
    
    $('#deleteForm').on('submit', function(e) {
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Invalid form action. Please try again.');
            return false;
        }
    });
});
</script>
@endpush