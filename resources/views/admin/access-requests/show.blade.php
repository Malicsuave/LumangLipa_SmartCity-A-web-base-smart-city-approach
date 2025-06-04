@extends('layouts.admin.master')

@section('title', 'Review Access Request')

@section('content')
    <div class="container-fluid">
        <!-- Page header with back button -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Review Access Request</h1>
            <a href="{{ route('admin.access-requests.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Back to All Requests
            </a>
        </div>
        
        <!-- Status Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Request Information Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Request Details</h6>
                <span class="badge badge-warning p-2">Pending Review</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="mb-1">User Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th width="40%" class="bg-light">Provided Name</th>
                                            <td>{{ $accessRequest->name ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">User Account Name</th>
                                            <td>{{ $accessRequest->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Email</th>
                                            <td>{{ $accessRequest->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Account Created</th>
                                            <td>{{ $accessRequest->user->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="mb-1">Request Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th width="40%" class="bg-light">Request ID</th>
                                            <td># {{ $accessRequest->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Role Requested</th>
                                            <td>
                                                <span class="badge badge-info">{{ $accessRequest->role->name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Submitted On</th>
                                            <td>{{ $accessRequest->requested_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-2">Reason for Request</h5>
                    <div class="border rounded p-3 bg-light">
                        {{ $accessRequest->reason }}
                    </div>
                </div>

                <div class="mt-4">
                    <h5 class="mb-3">Take Action</h5>
                    <div class="d-flex">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#approveModal">
                            <i class="fe fe-check mr-1" style="color: white !important;"></i> Approve Request
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal">
                            <i class="fe fe-x mr-1" style="color: white !important;"></i> Deny Request
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.access-requests.approve', $accessRequest) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel">Approve Access Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to grant <strong>{{ $accessRequest->role->name }}</strong> access to <strong>{{ $accessRequest->user->name }}</strong>?</p>
                            <p class="small text-muted">This will:</p>
                            <ul class="small text-muted">
                                <li>Assign the {{ $accessRequest->role->name }} role to this user</li>
                                <li>Grant them access to the appropriate features</li>
                                <li>Send them an email notification</li>
                            </ul>
                            
                            <div class="form-group mt-3">
                                <label for="notes">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes that will be included in the approval email..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve Access</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                                
        <!-- Deny Modal -->
        <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.access-requests.deny', $accessRequest) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="denyModalLabel">Deny Access Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to deny this access request from <strong>{{ $accessRequest->user->name }}</strong>?</p>
                            
                            <div class="form-group">
                                <label for="denial_reason">Reason for Denial <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="denial_reason" name="denial_reason" rows="3" required placeholder="Provide a reason for denying this request. This will be sent to the user."></textarea>
                                <small class="form-text text-muted">This explanation will be sent to the user in their denial notification email.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Deny Access</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection