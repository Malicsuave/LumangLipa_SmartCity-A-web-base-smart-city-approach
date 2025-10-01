@extends('layouts.admin.master')

@section('title', 'Admin Approvals Management')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h1 class="h3 mb-0 text-gray-800">Admin Approvals Management</h1>
                        <p class="text-muted mb-0">Manage Gmail accounts authorized for admin access</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.approvals.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus mr-2"></i>
                            Add New Admin
                        </a>
                        <a href="{{ route('admin.access-requests.index') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-clock mr-2"></i>
                            Pending Requests
                        </a>
                    </div>
                </div>
                
                <!-- Admin Approvals Table -->
                <div class="card shadow-lg border-0 admin-card-shadow">
                    <div class="card-header">
                        <strong class="card-title">Approved Admin Accounts</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="approvalsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="min-width: 180px;">Email</th>
                                        <th style="width: 130px;">Role</th>
                                        <th style="width: 80px;">Status</th>
                                        <th style="min-width: 140px;">Approved By</th>
                                        <th style="width: 140px;">Approved Date</th>
                                        <th style="width: 80px;" class="text-center">User Created</th>
                                        <th style="width: 80px;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvals as $approval)
                                        @php
                                            $isLastTwo = $loop->remaining < 2;
                                        @endphp
                                        <tr class="{{ $approval->is_active ? '' : 'text-muted' }}">
                                            <td class="text-truncate" style="max-width: 200px;" title="{{ $approval->email }}">
                                                {{ $approval->email }}
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-primary">
                                                    {{ $approval->role->name ?? 'No Role' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($approval->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-truncate" style="max-width: 150px;" title="{{ $approval->approved_by }}">
                                                {{ $approval->approved_by }}
                                            </td>
                                            <td>{{ $approval->approved_at ? $approval->approved_at->format('M d, Y H:i') : 'N/A' }}</td>
                                            <td class="text-center">
                                                @if($approval->user)
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-warning">Not Yet</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit-approval" href="javascript:void(0)" data-id="{{ $approval->id }}" data-email="{{ $approval->email }}" data-role="{{ $approval->role->name ?? 'No Role' }}">
                                                            <i class="fas fa-edit mr-2"></i>Edit
                                                        </a>
                                                        <a class="dropdown-item toggle-approval" href="javascript:void(0)" data-id="{{ $approval->id }}" data-email="{{ $approval->email }}" data-active="{{ $approval->is_active ? 'true' : 'false' }}">
                                                            @if($approval->is_active)
                                                                <i class="fas fa-ban text-warning mr-2"></i>Deactivate
                                                            @else
                                                                <i class="fas fa-check-circle text-success mr-2"></i>Activate
                                                            @endif
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete-approval" href="javascript:void(0)" data-id="{{ $approval->id }}" data-email="{{ $approval->email }}" data-role="{{ $approval->role->name ?? 'No Role' }}">
                                                            <i class="fas fa-trash-alt text-danger mr-2"></i>Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
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
                                                    <h4>No approvals found</h4>
                                                    <p class="text-muted">
                                                        No approvals match your search criteria.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Approved By</th>
                                        <th>Approved Date</th>
                                        <th class="text-center">User Created</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Admin Approval Information -->
               
            </div>
        </div>
    </div>

    <!-- Edit Confirmation Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Admin Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to edit this admin approval?</p>
                    <div class="alert alert-info">
                        <strong>Email:</strong> <span id="editEmail"></span><br>
                        <strong>Role:</strong> <span id="editRole"></span>
                    </div>
                    <p class="text-primary">This will redirect you to the edit page.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmEdit">Proceed to Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Confirmation Modal -->
    <div class="modal fade" id="toggleModal" tabindex="-1" role="dialog" aria-labelledby="toggleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleModalLabel">Toggle Admin Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to <strong id="toggleAction"></strong> this admin approval?</p>
                    <div class="alert alert-warning">
                        <strong>Email:</strong> <span id="toggleEmail"></span>
                    </div>
                    <p class="text-info">This will change the active status of the admin account.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmToggle">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Admin Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this admin approval?</p>
                    <div class="alert alert-danger">
                        <i class="fe fe-alert-triangle fe-16 mr-2"></i>
                        <strong>Email:</strong> <span id="deleteEmail"></span><br>
                        <strong>Role:</strong> <span id="deleteRole"></span>
                    </div>
                    <div class="alert alert-danger">
                        <i class="fe fe-alert-triangle fe-16 mr-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. The admin approval will be permanently removed from the system.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete Permanently</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable with shared helpers to match Residents/Documents
            if (window.DataTableHelpers && document.getElementById('approvalsTable')) {
                DataTableHelpers.initDataTable('#approvalsTable', {
                    buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    order: [[ 4, "desc" ]],
                    columnDefs: [
                        { "orderable": false, "targets": -1 },
                        { "orderable": false, "targets": -2 }
                    ]
                });
            }
            let currentAction = null;
            let currentId = null;

            // Edit approval handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-approval')) {
                    e.preventDefault();
                    const element = e.target.closest('.edit-approval');
                    const id = element.getAttribute('data-id');
                    const email = element.getAttribute('data-email');
                    const role = element.getAttribute('data-role');
                    
                    // Set modal content
                    document.getElementById('editEmail').textContent = email;
                    document.getElementById('editRole').textContent = role;
                    
                    // Set up confirm button
                    document.getElementById('confirmEdit').onclick = function() {
                        window.location.href = '/admin/approvals/' + id + '/edit';
                    };
                    
                    // Show modal
                    $('#editModal').modal('show');
                }
            });

            // Toggle approval handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.toggle-approval')) {
                    e.preventDefault();
                    const element = e.target.closest('.toggle-approval');
                    const id = element.getAttribute('data-id');
                    const email = element.getAttribute('data-email');
                    const isActive = element.getAttribute('data-active') === 'true';
                    
                    const action = isActive ? 'deactivate' : 'activate';
                    
                    // Set modal content
                    document.getElementById('toggleAction').textContent = action;
                    document.getElementById('toggleEmail').textContent = email;
                    
                    // Set up confirm button
                    document.getElementById('confirmToggle').onclick = function() {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/approvals/' + id + '/toggle';
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'PATCH';
                        
                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    };
                    
                    // Show modal
                    $('#toggleModal').modal('show');
                }
            });

            // Delete approval handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-approval')) {
                    e.preventDefault();
                    const element = e.target.closest('.delete-approval');
                    const id = element.getAttribute('data-id');
                    const email = element.getAttribute('data-email');
                    const role = element.getAttribute('data-role');
                    
                    // Set modal content
                    document.getElementById('deleteEmail').textContent = email;
                    document.getElementById('deleteRole').textContent = role;
                    
                    // Set up confirm button
                    document.getElementById('confirmDelete').onclick = function() {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/approvals/' + id;
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        
                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    };
                    
                    // Show modal
                    $('#deleteModal').modal('show');
                }
            });
        });
    </script>

    <style>
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .table th, .table td {
            white-space: nowrap;
        }
    }
    </style>
@endsection