@extends('layouts.admin.master')

@section('title', 'Admin Approvals Management')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h2 class="h5 page-title">Admin Approvals Management</h2>
                        <p class="text-muted">Manage Gmail accounts authorized for admin access</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.approvals.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus fe-16 mr-2"></i>
                            Add New Admin
                        </a>
                        <a href="{{ route('admin.access-requests.index') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fe fe-clock fe-16 mr-2"></i>
                            Pending Requests
                        </a>
                    </div>
                </div>
                
                <!-- Status Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Admin Approvals Table -->
                <div class="card shadow-lg border-0" style="box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;">
                    <div class="card-header">
                        <strong class="card-title">Approved Admin Accounts</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
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
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-{{ $approval->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fe fe-more-vertical fe-16"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-{{ $approval->id }}">
                                                        <a class="dropdown-item" href="#" onclick="confirmEdit('{{ $approval->id }}', '{{ $approval->email }}', '{{ $approval->role->name ?? 'No Role' }}')">
                                                            <i class="fe fe-edit-2 fe-16 mr-2 text-primary"></i>Edit
                                                        </a>
                                                        
                                                        <form id="toggle-form-{{ $approval->id }}" method="POST" action="{{ route('admin.approvals.toggle', $approval->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="button" class="dropdown-item" onclick="confirmToggle('{{ $approval->id }}', '{{ $approval->email }}', {{ $approval->is_active ? 'true' : 'false' }})">
                                                                @if($approval->is_active)
                                                                    <i class="fe fe-slash fe-16 mr-2 text-warning"></i>Deactivate
                                                                @else
                                                                    <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Activate
                                                                @endif
                                                            </button>
                                                        </form>
                                                        
                                                        <div class="dropdown-divider"></div>
                                                        <form id="delete-form-{{ $approval->id }}" method="POST" action="{{ route('admin.approvals.destroy', $approval->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger" onclick="confirmDelete('{{ $approval->id }}', '{{ $approval->email }}', '{{ $approval->role->name ?? 'No Role' }}')">
                                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No admin approvals found. Use the "Add New Admin" button to authorize Gmail accounts.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Admin Approval Information -->
               
            </div>
        </div>
    </div>

    <script>
        function confirmEdit(id, email, role) {
            // Custom JavaScript confirmation dialog for editing
            if (confirm('Edit Admin Approval\n\nEmail: ' + email + '\nRole: ' + role + '\n\nProceed with editing?')) {
                // Redirect to the edit route if confirmed
                window.location.href = '/admin/approvals/' + id + '/edit';
            }
        }

        function confirmToggle(id, email, isActive) {
            // Custom JavaScript confirmation dialog for toggling active status
            var action = isActive ? 'deactivate' : 'activate';
            if (confirm('Are you sure you want to ' + action + ' this admin approval?\n\nEmail: ' + email)) {
                // Submit the toggle form if confirmed
                document.getElementById('toggle-form-' + id).submit();
            }
        }

        function confirmDelete(id, email, role) {
            // Custom JavaScript confirmation dialog for deletion
            if (confirm('Are you sure you want to delete this admin approval?\n\nEmail: ' + email + '\nRole: ' + role + '\n\nThis action cannot be undone.')) {
                // Submit the delete form if confirmed
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>

    <style>
    /* Additional styles to enhance table responsiveness */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Make sure the table doesn't expand too wide on mobile */
    @media (max-width: 768px) {
        .table th, .table td {
            white-space: nowrap;
        }
    }
    </style>
@endsection