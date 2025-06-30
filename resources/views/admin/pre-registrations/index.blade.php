@extends('layouts.admin.master')

@section('title', 'Pre-Registration Management')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">Pre-Registration Management</h2>
                    <p class="text-muted">Review and approve resident pre-registration applications</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('public.pre-registration.create') }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="fe fe-external-link fe-16 mr-2"></i>View Public Form
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow health-metric-card metric-card metric-card-1">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3 text-center">
                                    <span class="circle circle-sm bg-primary metric-icon">
                                        <i class="fe fe-users text-white"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <p class="small text-muted mb-0">Total Applications</p>
                                    <span class="h3 metric-counter">{{ $stats['total'] }}</span>
                                    <span class="small text-muted">Submitted</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow health-metric-card metric-card metric-card-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3 text-center">
                                    <span class="circle circle-sm bg-warning metric-icon">
                                        <i class="fe fe-clock text-white"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <p class="small text-muted mb-0">Pending Review</p>
                                    <span class="h3 metric-counter">{{ $stats['pending'] }}</span>
                                    <span class="small text-muted">Applications</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow health-metric-card metric-card metric-card-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3 text-center">
                                    <span class="circle circle-sm bg-success metric-icon">
                                        <i class="fe fe-check-circle text-white"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <p class="small text-muted mb-0">Approved</p>
                                    <span class="h3 metric-counter">{{ $stats['approved'] }}</span>
                                    <span class="small text-muted">Applications</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow health-metric-card metric-card metric-card-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3 text-center">
                                    <span class="circle circle-sm bg-danger metric-icon">
                                        <i class="fe fe-x-circle text-white"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <p class="small text-muted mb-0">Rejected</p>
                                    <span class="h3 metric-counter">{{ $stats['rejected'] }}</span>
                                    <span class="small text-muted">Applications</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.pre-registrations.index') }}" id="filterForm">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search by name, email, or phone..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fe fe-search fe-16"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.pre-registrations.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fe fe-refresh-cw fe-16 mr-1"></i>Clear Filters
                            </a>
                        @else
                            <button type="submit" class="btn btn-outline-secondary w-100" disabled>
                                <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                            </button>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Applications Table -->
            <div class="card shadow-lg border-0" style="box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;">
                <div class="card-header">
                    <strong class="card-title">Pre-Registration Applications</strong>
                </div>
                <div class="card-body">
                    @if($preRegistrations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Age</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preRegistrations as $registration)
                                        <tr>
                                            <td>
                                                <strong>{{ $registration->full_name }}</strong>
                                                @if($registration->is_senior_citizen)
                                                    <br><small class="badge badge-warning">Senior Citizen</small>
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
                                            <td>{{ $registration->created_at->format('M d, Y') }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-{{ $registration->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fe fe-more-vertical fe-16"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-{{ $registration->id }}">
                                                        <a href="{{ route('admin.pre-registrations.show', $registration) }}" class="dropdown-item">
                                                            <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                        </a>
                                                        
                                                        @if($registration->status === 'pending')
                                                            <div class="dropdown-divider"></div>
                                                            <button type="button" class="dropdown-item" onclick="approveRegistration({{ $registration->id }})">
                                                                <i class="fe fe-check fe-16 mr-2 text-success"></i>Approve
                                                            </button>
                                                            <button type="button" class="dropdown-item" onclick="rejectRegistration({{ $registration->id }})">
                                                                <i class="fe fe-x fe-16 mr-2 text-warning"></i>Reject
                                                            </button>
                                                        @endif
                                                        
                                                        <div class="dropdown-divider"></div>
                                                        <button type="button" class="dropdown-item text-danger" onclick="deleteRegistration({{ $registration->id }})">
                                                            <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $preRegistrations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h4>No pre-registrations found</h4>
                            <p class="text-muted">
                                @if(request()->hasAny(['search', 'status']))
                                    No applications match your search criteria. <a href="{{ route('admin.pre-registrations.index') }}">Clear all filters</a>
                                @else
                                    No pre-registration applications have been submitted yet.
                                @endif
                            </p>
                            <a href="{{ route('public.pre-registration.create') }}" class="btn btn-primary" target="_blank">
                                <i class="fe fe-external-link fe-16 mr-2"></i>View Public Registration Form
                            </a>
                        </div>
                    @endif
                </div>
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
                    <i class="fe fe-info"></i> This will create a resident record and send a digital ID to the applicant's email.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fe fe-check"></i> Approve
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
                        <i class="fe fe-x"></i> Reject
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
                    <i class="fe fe-alert-triangle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fe fe-trash-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Define functions in global scope
window.approveRegistration = function(id) {
    console.log('Approve function called for ID:', id);
    const form = document.getElementById('approveForm');
    if (form) {
        const newAction = `/admin/pre-registrations/${id}/approve`;
        form.action = newAction;
        console.log('Form action set to:', form.action);
        console.log('Form method:', form.method);
        console.log('Form has CSRF token:', form.querySelector('input[name="_token"]') ? 'Yes' : 'No');
        
        // Test form submission
        form.addEventListener('submit', function(e) {
            console.log('Form is submitting to:', this.action);
            console.log('Form data:', new FormData(this));
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
        console.log('Form action set to:', form.action);
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
        console.log('Form action set to:', form.action);
        $('#deleteModal').modal('show');
    } else {
        console.error('Delete form not found');
    }
};

// Initialize when document is ready
$(document).ready(function() {
    console.log('Pre-registration page initialized');
    
    // Test if we can find all the forms
    console.log('Approve form found:', document.getElementById('approveForm') ? 'Yes' : 'No');
    console.log('Reject form found:', document.getElementById('rejectForm') ? 'Yes' : 'No');
    console.log('Delete form found:', document.getElementById('deleteForm') ? 'Yes' : 'No');
    
    // Force Bootstrap dropdown initialization
    if (typeof $.fn.dropdown !== 'undefined') {
        $('.dropdown-toggle').dropdown();
        console.log('Bootstrap dropdowns initialized');
    } else {
        console.log('Bootstrap dropdown not available, using manual implementation');
    }
    
    // Manual dropdown handling with better debugging
    $('.btn-icon').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Dropdown button clicked');
        
        // Close all other dropdowns
        $('.dropdown-menu').removeClass('show');
        
        // Toggle this dropdown
        const menu = $(this).next('.dropdown-menu');
        menu.toggleClass('show');
        console.log('Dropdown toggled, now showing:', menu.hasClass('show'));
        console.log('Menu items found:', menu.find('.dropdown-item').length);
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });
    
    // Prevent dropdown from closing when clicking inside
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Form submission handlers with better debugging
    $('#approveForm').on('submit', function(e) {
        console.log('Approve form submitting to:', this.action);
        console.log('Form is valid:', this.checkValidity());
        
        // Check if form action is properly set
        if (!this.action || this.action.includes('undefined')) {
            console.error('Invalid form action detected: action is undefined');
            e.preventDefault();
            alert('Error: Form action is not set correctly. Please try clicking approve again.');
            return false;
        }
        
        // Check if the action contains the expected pattern
        if (!this.action.includes('/admin/pre-registrations/') || !this.action.includes('/approve')) {
            console.error('Invalid form action detected: incorrect URL pattern');
            e.preventDefault();
            alert('Error: Form action is not set correctly. Please try clicking approve again.');
            return false;
        }
        
        console.log('Form validation passed, submitting...');
        // Allow form to submit normally
    });
    
    $('#rejectForm').on('submit', function(e) {
        console.log('Reject form submitting to:', this.action);
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
        console.log('Delete form submitting to:', this.action);
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Invalid form action. Please try again.');
            return false;
        }
    });
    
    console.log('All event handlers initialized');
    console.log('Functions available:', {
        approveRegistration: typeof window.approveRegistration,
        rejectRegistration: typeof window.rejectRegistration,
        deleteRegistration: typeof window.deleteRegistration
    });
});
</script>
@endpush