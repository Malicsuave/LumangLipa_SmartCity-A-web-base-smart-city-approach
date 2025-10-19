@extends('layouts.admin.master')

@push('styles')
@include('admin.components.datatable-styles')
@endpush

@push('scripts')
@include('admin.components.datatable-scripts')
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>
<script>
$(function () {
    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable('#documentsTable')) {
        $('#documentsTable').DataTable().destroy();
    }

    // Initialize DataTable for documents table using the same helper as Residents
    const documentsTable = DataTableHelpers.initDataTable("#documentsTable", {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        pageLength: 10,
        lengthChange: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        columnDefs: [
            { "orderable": false, "targets": -1 },
            { "responsivePriority": 1, "targets": 0 },
            { "responsivePriority": 2, "targets": 1 },
            { "responsivePriority": 3, "targets": 3 },
            { "responsivePriority": 4, "targets": 2 },
            { "responsivePriority": 5, "targets": 4 },
            { "responsivePriority": 10, "targets": -1 }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });

    // Helper functions for modals
    window.getStatusBadge = function(status) {
        const documentStatuses = {
            'claimed': '<span class="badge badge-info">Claimed</span>',
            'rejected': '<span class="badge badge-danger">Rejected</span>',
            'pending': '<span class="badge badge-warning">Pending</span>',
            'approved': '<span class="badge badge-success">Approved</span>'
        };
        return documentStatuses[status] || '<span class="badge badge-secondary">Unknown</span>';
    }
    window.formatDate = function(dateString) {
        try {
            const d = new Date(dateString);
            return isNaN(d.getTime()) ? (dateString || 'N/A') : d.toLocaleString();
        } catch (e) {
            return dateString || 'N/A';
        }
    };
    window.enlargeReceipt = function(imageSrc) {
        $('#enlargeReceiptBody').html('<img src="' + imageSrc + '" alt="Payment Receipt" class="img-fluid">');
        $('#enlargeReceiptModal').modal('show');
    }

    // Print document functionality
    $(document).on('click', '#printDocumentBtn', function() {
        if (window.currentDocumentRequestId) {
            window.open('/admin/documents/' + window.currentDocumentRequestId + '/print', '_blank');
        }
    });

    // Mark as claimed confirmation handler
    $(document).on('click', '#confirmMarkClaimed', function() {
        if (!window.currentRequestId) {
            console.error('No request ID available');
            return;
        }

        // Disable button to prevent double clicks
        $(this).prop('disabled', true).text('Processing...');

        $.ajax({
            url: '/admin/documents/' + window.currentRequestId + '/mark-claimed',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Close modal
                    $('#markClaimedModal').modal('hide');
                    
                    // Show success message with global helper
                    showSuccess(response.message);
                    
                    // Reload the page to refresh the table
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    showError(response.message || 'Failed to mark document as claimed.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking document as claimed:', error);
                handleAjaxError(xhr, status, error, 'An error occurred while marking the document as claimed.');
            },
            complete: function() {
                // Re-enable button
                $('#confirmMarkClaimed').prop('disabled', false).html('<i class="fe fe-check-square fe-16 mr-2 text-white"></i> Mark as Claimed');
            }
        });
    });

    // Approve request confirmation handler
    $(document).on('click', '#confirmApprove', function() {
        if (!window.currentRequestId) {
            console.error('No request ID available');
            return;
        }

        // Disable button to prevent double clicks
        $(this).prop('disabled', true).text('Processing...');

        $.ajax({
            url: '/admin/documents/' + window.currentRequestId + '/approve',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Close modal
                    $('#approveModal').modal('hide');
                    
                    // Show success message with global helper
                    showSuccess(response.message);
                    
                    // Reload the page to refresh the table
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    showError(response.message || 'Failed to approve document request.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error approving document request:', error);
                handleAjaxError(xhr, status, error, 'An error occurred while approving the document request.');
            },
            complete: function() {
                // Re-enable button
                $('#confirmApprove').prop('disabled', false).html('<i class="fe fe-check-circle fe-16 mr-2 text-white"></i> Approve Request');
            }
        });
    });

    // Reject form submission handler
    $(document).on('submit', '#rejectForm', function(e) {
        e.preventDefault();
        
        if (!window.currentRequestId) {
            console.error('No request ID available');
            return;
        }

        var rejectionReason = $('#rejection_reason').val().trim();
        if (!rejectionReason) {
            showWarning('Please provide a reason for rejection.');
            return;
        }

        // Disable submit button to prevent double submission
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: '/admin/documents/' + window.currentRequestId + '/reject',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                rejection_reason: rejectionReason
            },
            success: function(response) {
                if (response.success) {
                    // Close modal
                    $('#rejectModal').modal('hide');
                    
                    // Reset form
                    $('#rejectForm')[0].reset();
                    
                    // Show success message with global helper
                    showSuccess(response.message);
                    
                    // Reload the page to refresh the table
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    showError(response.message || 'Failed to reject document request.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error rejecting document request:', error);
                handleAjaxError(xhr, status, error, 'An error occurred while rejecting the document request.');
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).text('Reject Request');
            }
        });
    });
});
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Document Requests</h1>
                <p class="text-muted mb-0">Manage barangay document requests and approvals</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.documents') }}" class="btn btn-info">
                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                </a>
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
                @if($documentRequests->count() > 0)
                <table id="documentsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Resident Name</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Date Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documentRequests as $request)
                        <tr>
                            <td><strong>#{{ $request->id }}</strong></td>
                            <td><strong>{{ $request->resident->first_name ?? 'N/A' }} {{ $request->resident->last_name ?? '' }}</strong></td>
                            <td>{{ $request->document_type }}</td>
                            <td>
                                @if($request->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($request->status == 'claimed')
                                    <span class="badge badge-info">Claimed</span>
                                @elseif($request->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="viewDetails({{ $request->id }})">
                                            <i class="fas fa-eye mr-2" aria-hidden="true"></i>View Details
                                        </a>
                                        @if($request->status == 'pending')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-success" href="javascript:void(0)" onclick="approveRequest({{ $request->id }})">
                                                <i class="fas fa-check mr-2" aria-hidden="true"></i>Approve
                                            </a>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="rejectRequest({{ $request->id }})">
                                                <i class="fas fa-times mr-2" aria-hidden="true"></i>Reject
                                            </a>
                                        @elseif($request->status == 'approved')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-info" href="javascript:void(0)" onclick="markAsClaimed({{ $request->id }})">
                                                <i class="fas fa-check-square mr-2" aria-hidden="true"></i>Mark as Claimed
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="previewDocument({{ $request->id }})">
                                                <i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>Preview Document
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
                            <th>Request ID</th>
                            <th>Resident Name</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Date Requested</th>
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
                    <h4>No document requests found</h4>
                    <p class="text-muted">No document requests have been submitted yet.</p>
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
                    <div class="col-12 mb-3">
                        <div class="section-title">Purpose</div>
                        <div id="modal-purpose" class="purpose-box"></div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-2 text-center">Payment Receipt</h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div id="modal-receipt-container" class="mb-1">
                                <span class="text-muted">No receipt uploaded.</span>
                            </div>
                            <small class="text-muted" id="receipt-note" style="font-size:0.82rem;line-height:1.2;background:#f8f9fa;padding:2px 8px;border-radius:6px;display:inline-block;margin-top:2px;">Click the receipt image</small>
                        </div>
                        <!-- Enlarged Receipt Modal -->
                        <div class="modal fade" id="enlargeReceiptModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-end align-items-center">
                                        <button type="button" class="btn p-0 border-0 bg-transparent" id="closeEnlargeReceipt" aria-label="Close" style="font-size:1.3rem;">
                                            <i class="fe fe-x" style="font-size:1.4rem;"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center p-2" id="enlargeReceiptBody" style="min-height:40px;">
                                        <!-- Content inserted by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
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
// Action functions for DataTable buttons
function viewDetails(requestId) {
    console.log('View details for request:', requestId);
    
    // Show loading state
    $('#modal-request-id').text('Loading...');
    $('#modal-document-type').text('Loading...');
    $('#modal-status').text('Loading...');
    $('#modal-requested-date').text('Loading...');
    $('#modal-resident-name').text('Loading...');
    $('#modal-barangay-id').text('Loading...');
    $('#modal-resident-address').text('Loading...');
    $('#modal-purpose').text('Loading...');
    $('#modal-receipt-container').html('<span class="text-muted">Loading...</span>');
    
    // Open modal first
    $('#viewDetailsModal').modal('show');
    
    // Fetch data via AJAX
    $.ajax({
        url: '/admin/documents/' + requestId,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Populate modal with data
            $('#modal-request-id').text('#' + response.id);
            $('#modal-document-type').text(response.document_type);
            $('#modal-status').html(getStatusBadge(response.status));
            $('#modal-requested-date').text(formatDate(response.created_at));
            $('#modal-resident-name').text((response.resident?.first_name || 'N/A') + ' ' + (response.resident?.last_name || ''));
            $('#modal-barangay-id').text(response.barangay_id || 'N/A');
            $('#modal-resident-address').text(response.resident?.address || 'N/A');
            $('#modal-purpose').text(response.purpose || 'No purpose specified');
            
            // Handle receipt - use receipt_path field
            if (response.receipt_path) {
                const fullReceiptUrl = response.receipt_path.startsWith('http') ? response.receipt_path : '/storage/' + response.receipt_path;
                $('#modal-receipt-container').html(
                    '<img src="' + fullReceiptUrl + '" alt="Payment Receipt" class="img-fluid" style="max-width: 200px; cursor: pointer;" onclick="enlargeReceipt(\'' + fullReceiptUrl + '\')">'
                );
            } else {
                $('#modal-receipt-container').html('<span class="text-muted">No receipt uploaded.</span>');
            }
            
            // Show claimed info if status is claimed
            if (response.status === 'claimed') {
                $('#claimed-info-row').show();
                $('#claimed-by-row').show();
                $('#modal-claimed-at').text(formatDate(response.claimed_at));
                $('#modal-claimed-by').text(response.claimed_by || 'N/A');
            } else {
                $('#claimed-info-row').hide();
                $('#claimed-by-row').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching document details:', error);
            $('#modal-request-id').text('Error loading data');
            $('#modal-document-type').text('Error');
            $('#modal-status').text('Error');
            $('#modal-requested-date').text('Error');
            $('#modal-resident-name').text('Error');
            $('#modal-barangay-id').text('Error');
            $('#modal-resident-address').text('Error');
            $('#modal-purpose').text('Error loading data');
            $('#modal-receipt-container').html('<span class="text-danger">Error loading receipt</span>');
        }
    });
    
    return false;
}

function approveRequest(requestId) {
    console.log('Approve request:', requestId);
    window.currentRequestId = requestId;
    $('#approveModal').modal('show');
    return false;
}

function rejectRequest(requestId) {
    console.log('Reject request:', requestId);
    window.currentRequestId = requestId;
    $('#rejectModal').modal('show');
    return false;
}

function markAsClaimed(requestId) {
    console.log('Mark as claimed request:', requestId);
    window.currentRequestId = requestId;
    $('#markClaimedModal').modal('show');
    return false;
}

function previewDocument(requestId) {
    console.log('Preview document for request:', requestId);
    
    // Set the iframe source to the document preview URL
    $('#documentFrame').attr('src', '/admin/documents/' + requestId + '/view');
    $('#documentPreviewModal').modal('show');
    
    // Store request ID for print functionality
    window.currentDocumentRequestId = requestId;
    
    return false;
}
</script>
@endsection