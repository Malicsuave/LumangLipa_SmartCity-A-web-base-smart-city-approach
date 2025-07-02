@extends('layouts.admin.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-common.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Document Requests</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Manage Document Requests</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">This is the document requests management module for Barangay Captain and Secretary.</p>
                
                @if($documentRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Resident</th>
                                <th>Document Type</th>
                                <th>Date Requested</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->resident_name }}</td>
                                <td>{{ $request->document_type }}</td>
                                <td>{{ $request->requested_at->format('M j, Y') }}</td>
                                <td>{!! $request->status_badge !!}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-doc{{ $request->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-doc{{ $request->id }}">
                                            <a class="dropdown-item view-details" href="#" data-id="{{ $request->id }}">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            @if($request->status === 'pending')
                                                <a class="dropdown-item approve-request" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Approve
                                                </a>
                                                <a class="dropdown-item reject-request" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-x-circle fe-16 mr-2 text-danger"></i>Reject
                                                </a>
                                            @elseif($request->status === 'approved')
                                                <a class="dropdown-item view-document" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-file-text fe-16 mr-2 text-info"></i>View Document
                                                </a>
                                                <a class="dropdown-item print-document" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-printer fe-16 mr-2 text-secondary"></i>Print Document
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
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $documentRequests->firstItem() }} to {{ $documentRequests->lastItem() }} of {{ $documentRequests->total() }} document requests
                    </div>
                    {{ $documentRequests->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fe fe-file-plus fe-48 text-muted mb-3"></i>
                    <h5 class="text-muted">No Document Requests Found</h5>
                    <p class="text-muted">There are currently no document requests to display.</p>
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
                    <div class="col-md-6">
                        <h6>Request Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Request ID:</strong></td>
                                <td id="modal-request-id"></td>
                            </tr>
                            <tr>
                                <td><strong>Document Type:</strong></td>
                                <td id="modal-document-type"></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td id="modal-status"></td>
                            </tr>
                            <tr>
                                <td><strong>Date Requested:</strong></td>
                                <td id="modal-requested-date"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Resident Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td id="modal-resident-name"></td>
                            </tr>
                            <tr>
                                <td><strong>Barangay ID:</strong></td>
                                <td id="modal-barangay-id"></td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td id="modal-resident-address"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Purpose</h6>
                        <p id="modal-purpose" class="border p-3 rounded bg-light"></p>
                    </div>
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
document.addEventListener('DOMContentLoaded', function() {
    let currentRequestId = null;

    // View Details
    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const requestId = this.getAttribute('data-id');
            
            fetch(`/admin/documents/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-request-id').textContent = data.id;
                    document.getElementById('modal-document-type').textContent = data.document_type;
                    document.getElementById('modal-status').innerHTML = getStatusBadge(data.status);
                    document.getElementById('modal-requested-date').textContent = new Date(data.requested_at).toLocaleDateString();
                    document.getElementById('modal-resident-name').textContent = data.resident ? 
                        `${data.resident.first_name} ${data.resident.middle_name || ''} ${data.resident.last_name}`.trim() : 'Unknown';
                    document.getElementById('modal-barangay-id').textContent = data.barangay_id;
                    document.getElementById('modal-resident-address').textContent = data.resident ? data.resident.address : 'N/A';
                    document.getElementById('modal-purpose').textContent = data.purpose;
                    
                    $('#viewDetailsModal').modal('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading request details');
                });
        });
    });

    // View Document
    document.querySelectorAll('.view-document').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const requestId = this.getAttribute('data-id');
            const documentFrame = document.getElementById('documentFrame');
            const printBtn = document.getElementById('printDocumentBtn');
            
            // Set iframe src to view document
            documentFrame.src = `/admin/documents/${requestId}/view`;
            
            // Set print button to print the iframe content
            printBtn.onclick = function() {
                const iframe = document.getElementById('documentFrame');
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
            
            $('#documentPreviewModal').modal('show');
        });
    });

    // Print Document directly
    document.querySelectorAll('.print-document').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const requestId = this.getAttribute('data-id');
            
            // Create a hidden iframe for printing
            const printFrame = document.createElement('iframe');
            printFrame.style.display = 'none';
            printFrame.src = `/admin/documents/${requestId}/print`;
            
            printFrame.onload = function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
                
                // Remove the iframe after printing
                setTimeout(() => {
                    document.body.removeChild(printFrame);
                }, 1000);
            };
            
            document.body.appendChild(printFrame);
        });
    });

    // Approve Request
    document.querySelectorAll('.approve-request').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentRequestId = this.getAttribute('data-id');
            $('#approveModal').modal('show');
        });
    });

    // Confirm Approve
    document.getElementById('confirmApprove').addEventListener('click', function() {
        fetch(`/admin/documents/${currentRequestId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#approveModal').modal('hide');
                location.reload();
            } else {
                alert('Error approving request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error approving request');
        });
    });

    // Reject Request
    document.querySelectorAll('.reject-request').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentRequestId = this.getAttribute('data-id');
            $('#rejectModal').modal('show');
        });
    });

    // Handle reject form submission
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const rejectionReason = document.getElementById('rejection_reason').value;
        
        fetch(`/admin/documents/${currentRequestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rejection_reason: rejectionReason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#rejectModal').modal('hide');
                location.reload();
            } else {
                alert('Error rejecting request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting request');
        });
    });

    function getStatusBadge(status) {
        switch(status) {
            case 'pending':
                return '<span class="badge badge-warning">Pending</span>';
            case 'approved':
                return '<span class="badge badge-success">Approved</span>';
            case 'rejected':
                return '<span class="badge badge-danger">Rejected</span>';
            default:
                return '<span class="badge badge-secondary">Unknown</span>';
        }
    }
});
</script>
@endsection