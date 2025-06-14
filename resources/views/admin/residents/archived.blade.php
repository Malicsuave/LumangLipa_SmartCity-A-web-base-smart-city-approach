@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Archived</li>
@endsection

@section('page-title', 'Archived Residents')
@section('page-subtitle', 'View and manage archived resident records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-archive fe-16 mr-2"></i>Archived Residents</h4>
                        <p class="text-muted mb-0">View and restore archived resident records</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fe fe-users fe-16 mr-2"></i>Active Residents
                        </a>
                        <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
                            <i class="fe fe-user-plus fe-16 mr-2"></i>Register New Resident
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Bar -->
                <form action="{{ route('admin.residents.archived') }}" method="GET" id="searchForm">
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search archived residents by name, ID, contact..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            @if(request('search'))
                                <a href="{{ route('admin.residents.archived') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($archivedResidents->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Age/Gender</th>
                                <th>Contact</th>
                                <th>Archived Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedResidents as $resident)
                            <tr>
                                <td><strong>{{ $resident->barangay_id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($resident->photo)
                                                <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle opacity-75">
                                            @else
                                                <div class="avatar-letter rounded-circle bg-secondary">{{ substr($resident->first_name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong class="text-muted">{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                            @if($resident->middle_name)
                                                {{ substr($resident->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $resident->suffix }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-secondary">{{ $resident->type_of_resident }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $resident->sex }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $resident->contact_number ?: 'N/A' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $resident->deleted_at->format('M d, Y') }}</small>
                                    <br><small class="text-muted">{{ $resident->deleted_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted sr-only">Action</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item" 
                                               onclick="event.preventDefault(); confirmRestore('{{ $resident->id }}', '{{ $resident->first_name }} {{ $resident->last_name }}')">
                                                <i class="fe fe-rotate-ccw fe-16 mr-2 text-success"></i>Restore
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" class="dropdown-item text-danger" 
                                               onclick="event.preventDefault(); confirmPermanentDelete('{{ $resident->id }}', '{{ $resident->first_name }} {{ $resident->last_name }}')">
                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete Permanently
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Restore Form -->
                                    <form id="restore-form-{{ $resident->id }}" action="{{ route('admin.residents.restore', $resident->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    
                                    <!-- Permanent Delete Form -->
                                    <form id="permanent-delete-form-{{ $resident->id }}" action="{{ route('admin.residents.force-delete', $resident->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $archivedResidents->firstItem() ?? 0 }} to {{ $archivedResidents->lastItem() ?? 0 }} of {{ $archivedResidents->total() }} archived residents
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($archivedResidents->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $archivedResidents->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            
                            @for($i = 1; $i <= $archivedResidents->lastPage(); $i++)
                                <li class="page-item {{ $i == $archivedResidents->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $archivedResidents->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            @if($archivedResidents->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $archivedResidents->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" alt="No archived residents" class="img-fluid mb-3" width="200">
                    <h4>No archived residents found</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            No archived residents match your search criteria. <a href="{{ route('admin.residents.archived') }}">Clear search</a>
                        @else
                            There are no archived residents at the moment.
                        @endif
                    </p>
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-primary">
                        <i class="fe fe-users fe-16 mr-2"></i>View Active Residents
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Restore Resident</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore <strong id="restoreResidentName"></strong>?</p>
                <p class="text-success">This will move the resident back to the active list.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmRestore">Restore</button>
            </div>
        </div>
    </div>
</div>

<!-- Permanent Delete Confirmation Modal -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" role="dialog" aria-labelledby="permanentDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permanentDeleteModalLabel">Permanently Delete Resident</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete <strong id="permanentDeleteResidentName"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="fe fe-alert-triangle fe-16 mr-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All data related to this resident will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmPermanentDelete">Delete Permanently</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmRestore(id, name) {
        document.getElementById('restoreResidentName').textContent = name;
        document.getElementById('confirmRestore').onclick = function() {
            document.getElementById('restore-form-' + id).submit();
        };
        $('#restoreModal').modal('show');
    }

    function confirmPermanentDelete(id, name) {
        document.getElementById('permanentDeleteResidentName').textContent = name;
        document.getElementById('confirmPermanentDelete').onclick = function() {
            document.getElementById('permanent-delete-form-' + id).submit();
        };
        $('#permanentDeleteModal').modal('show');
    }
</script>
@endsection