@extends('layouts.admin.master')

@section('title', 'Access Requests')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h1 class="h3 mb-0 text-gray-800">Access Request Management</h1>
                        <p class="text-muted mb-0">Review and process user access requests for system roles</p>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh List
                        </button>
                    </div>
                </div>
                
                <!-- Pending Requests Card -->
                <div class="card shadow-lg border-0 mb-4 admin-card-shadow">
                    <div class="card-header">
                        <strong class="card-title">Pending Access Requests</strong>
                    </div>
                    <div class="card-body">
                        @if($pendingRequests->count() > 0)
                            <div class="table-responsive">
                                <table id="accessPendingTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>User</th>
                                            <th>Role Requested</th>
                                            <th>Requested On</th>
                                            <th width="25%">Reason</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingRequests as $request)
                                            @php
                                                $isLastTwo = $loop->remaining < 2;
                                                $dropdownItems = [];
                                                $dropdownItems[] = [
                                                    'label' => 'Review Details',
                                                    'icon' => 'fe fe-eye fe-16 text-primary',
                                                    'class' => '',
                                                    'href' => route('admin.access-requests.show', $request),
                                                ];
                                                $dropdownItems[] = ['divider' => true];
                                                $dropdownItems[] = [
                                                    'label' => 'Approve Request',
                                                    'icon' => 'fe fe-check-circle fe-16 text-success',
                                                    'class' => '',
                                                    'attrs' => "data-toggle=\"modal\" data-target=\"#approveModal{$request->id}\" href='#'",
                                                ];
                                                $dropdownItems[] = [
                                                    'label' => 'Deny Request',
                                                    'icon' => 'fe fe-x-circle fe-16 text-danger',
                                                    'class' => '',
                                                    'attrs' => "data-toggle=\"modal\" data-target=\"#denyModal{$request->id}\" href='#'",
                                                ];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="font-weight-bold">{{ $request->name ?? 'Not provided' }}</div>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $request->user ? $request->user->name : 'Unknown User' }}</div>
                                                    <div class="small text-muted">{{ $request->user ? $request->user->email : 'No email' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-primary">
                                                        {{ $request->role ? $request->role->name : 'Unknown Role' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $request->requested_at ? $request->requested_at->format('M d, Y') : 'N/A' }}<br>
                                                    <small class="text-muted">{{ $request->requested_at ? $request->requested_at->format('h:i A') : '' }}</small>
                                                </td>
                                                <td>
                                                    <div class="text-sm">{{ \Illuminate\Support\Str::limit($request->reason, 100) }}</div>
                                                    @if(strlen($request->reason) > 100)
                                                        <a href="{{ route('admin.access-requests.show', $request) }}" class="small">Read more</a>
                                                    @endif
                                                </td>
                                                <td class="text-center table-actions-col">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="{{ route('admin.access-requests.show', $request) }}">
                                                                <i class="fas fa-eye mr-2"></i>Review Details
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#approveModal{{ $request->id }}">
                                                                <i class="fas fa-check-circle text-success mr-2"></i>Approve Request
                                                            </a>
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#denyModal{{ $request->id }}">
                                                                <i class="fas fa-times-circle text-danger mr-2"></i>Deny Request
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!-- Approve Modal -->
                                                    <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('admin.access-requests.approve', $request) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="approveModalLabel">Approve Access Request</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-left">
                                                                        <p>Are you sure you want to grant <strong>{{ $request->role ? $request->role->name : 'Unknown Role' }}</strong> access to <strong>{{ $request->user ? $request->user->name : 'Unknown User' }}</strong>?</p>
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="notes">Notes (Optional)</label>
                                                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes that will be included in the approval email..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-success">Approve Access</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Deny Modal -->
                                                    <div class="modal fade" id="denyModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('admin.access-requests.deny', $request) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="denyModalLabel">Deny Access Request</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-left">
                                                                        <p>Are you sure you want to deny this access request from <strong>{{ $request->user ? $request->user->name : 'Unknown User' }}</strong>?</p>
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="denial_reason">Reason for Denial <span class="text-danger">*</span></label>
                                                                            <textarea class="form-control" id="denial_reason" name="denial_reason" rows="3" required placeholder="Provide a reason for denying this request. This will be sent to the user."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-danger">Deny Access</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>User</th>
                                            <th>Role Requested</th>
                                            <th>Requested On</th>
                                            <th>Reason</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-3">
                                  <circle cx="60" cy="60" r="56" fill="#f3f4f6" stroke="#e5e7eb" stroke-width="4"/>
                                  <rect x="35" y="70" width="50" height="10" rx="5" fill="#e5e7eb"/>
                                  <ellipse cx="60" cy="54" rx="18" ry="20" fill="#e5e7eb"/>
                                  <ellipse cx="60" cy="54" rx="10" ry="12" fill="#f3f4f6"/>
                                  <circle cx="54" cy="52" r="2" fill="#bdbdbd"/>
                                  <circle cx="66" cy="52" r="2" fill="#bdbdbd"/>
                                  <rect x="56" y="58" width="8" height="2" rx="1" fill="#bdbdbd"/>
                                </svg>
                                <h4>No access requests found</h4>
                                <p class="text-muted">
                                    No access requests match your search criteria.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="card shadow-lg border-0 admin-card-shadow">
                    <div class="card-header">
                        <strong class="card-title">Recently Processed Requests</strong>
                    </div>
                    <div class="card-body">
                        @if($processedRequests->count() > 0)
                            <div class="table-responsive">
                                <table id="accessProcessedTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Processed By</th>
                                            <th>Processed On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($processedRequests as $request)
                                            <tr>
                                                <td>
                                                    <div class="font-weight-bold">{{ $request->name ?? 'Not provided' }}</div>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $request->user ? $request->user->name : 'Unknown User' }}</div>
                                                    <div class="small text-muted">{{ $request->user ? $request->user->email : 'No email' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-primary">
                                                        {{ $request->role ? $request->role->name : 'Unknown Role' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($request->status == 'approved')
                                                        <span class="badge badge-success">Approved</span>
                                                    @else
                                                        <span class="badge badge-danger">Denied</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($request->status == 'approved')
                                                        {{ optional($request->approver)->name ?? 'System' }}
                                                    @else
                                                        {{ optional($request->denier)->name ?? 'System' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($request->status == 'approved')
                                                        {{ $request->approved_at ? $request->approved_at->format('M d, Y h:i A') : 'N/A' }}
                                                    @else
                                                        {{ $request->denied_at ? $request->denied_at->format('M d, Y h:i A') : 'N/A' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Processed By</th>
                                            <th>Processed On</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No processed requests yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination for processed requests -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        @if(method_exists($processedRequests, 'firstItem'))
                            Showing {{ $processedRequests->firstItem() ?? 0 }} to {{ $processedRequests->lastItem() ?? 0 }} of {{ $processedRequests->total() }} processed requests
                        @else
                            Showing {{ $processedRequests->count() }} processed requests
                        @endif
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if(method_exists($processedRequests, 'onFirstPage'))
                                @if($processedRequests->onFirstPage())
                                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $processedRequests->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                                @endif
                                
                                @for($i = 1; $i <= $processedRequests->lastPage(); $i++)
                                    <li class="page-item {{ $i == $processedRequests->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $processedRequests->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($processedRequests->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $processedRequests->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                                @else
                                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                                @endif
                            @endif
                        </ul>
                    </nav>
                </div>
                
                <!-- Access Request Information -->
               
            </div>
        </div>
    </div>

    <script>
        function refreshPage() { window.location.reload(); }
        document.addEventListener('DOMContentLoaded', function() {
            if (window.DataTableHelpers) {
                if (document.getElementById('accessPendingTable')) {
                    DataTableHelpers.initDataTable('#accessPendingTable', {
                        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                        order: [[ 3, "desc" ]],
                        columnDefs: [
                            { "orderable": false, "targets": -1 }
                        ]
                    });
                }
                if (document.getElementById('accessProcessedTable')) {
                    DataTableHelpers.initDataTable('#accessProcessedTable', {
                        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                        order: [[ 5, "desc" ]]
                    });
                }
            }
        });
    </script>

    <style>
        .table-responsive,
        .card-body,
        .collapse,
        #filterSection {
            overflow: visible !important;
        }
        .dropdown-menu {
            z-index: 9999 !important;
        }
    </style>
@endsection