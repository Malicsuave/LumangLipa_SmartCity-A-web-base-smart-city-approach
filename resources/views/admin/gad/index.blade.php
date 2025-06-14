@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Gender & Development</li>
@endsection

@section('page-title', 'Gender & Development Management')
@section('page-subtitle', 'Manage Gender and Development records and programs')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-users fe-16 mr-2"></i>Gender & Development Records</h4>
                        <p class="text-muted mb-0">Manage GAD programs and track beneficiaries</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.gad.archived') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fe fe-archive fe-16 mr-2"></i>View Archived
                        </a>
                        <a href="{{ route('admin.gad.reports') }}" class="btn btn-outline-secondary mr-3">
                            <i class="fe fe-bar-chart fe-16 mr-2"></i>Generate Report
                        </a>
                        <a href="{{ route('admin.gad.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus fe-16 mr-2"></i>Add New GAD Record
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Bar -->
                <form action="{{ route('admin.gad.index') }}" method="GET" id="searchForm">
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search GAD records by resident name, ID, program..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            @if(request('search'))
                                <a href="{{ route('admin.gad.index') }}" class="btn btn-outline-secondary w-100">
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

                @if($gadRecords->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Gender Identity</th>
                                <th>Programs</th>
                                <th>Status</th>
                                <th>Special Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gadRecords as $gad)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($gad->resident && $gad->resident->photo)
                                                <img src="{{ $gad->resident->photo_url }}" alt="{{ $gad->resident->full_name }}" class="avatar-img rounded-circle">
                                            @else
                                                <div class="avatar-letter rounded-circle bg-primary">{{ $gad->resident ? substr($gad->resident->first_name, 0, 1) : 'U' }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $gad->resident->full_name ?? 'Unknown Resident' }}</strong>
                                            <br><small class="text-muted">ID: {{ $gad->resident->barangay_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $gad->gender_identity }}</td>
                                <td>
                                    @if($gad->programs_enrolled)
                                        @foreach($gad->programs_enrolled as $program)
                                            <span class="badge badge-soft-info">{{ $program }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-soft-secondary">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($gad->program_status)
                                        <span class="badge badge-soft-{{ $gad->program_status == 'Active' ? 'success' : ($gad->program_status == 'Completed' ? 'primary' : 'warning') }}">
                                            {{ $gad->program_status }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($gad->is_pregnant)
                                        <span class="badge badge-soft-warning">Pregnant</span>
                                    @endif
                                    @if($gad->is_solo_parent)
                                        <span class="badge badge-soft-success">Solo Parent</span>
                                    @endif
                                    @if($gad->is_vaw_case)
                                        <span class="badge badge-soft-danger">VAW Case</span>
                                    @endif
                                    @if(!$gad->is_pregnant && !$gad->is_solo_parent && !$gad->is_vaw_case)
                                        <span class="badge badge-soft-secondary">None</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted sr-only">Action</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('admin.gad.show', $gad->id) }}" class="dropdown-item">
                                                <i class="fe fe-eye fe-16 mr-2 text-info"></i>View Details
                                            </a>
                                            <a href="{{ route('admin.gad.edit', $gad->id) }}" class="dropdown-item">
                                                <i class="fe fe-edit-3 fe-16 mr-2 text-primary"></i>Edit Record
                                            </a>
                                            @if($gad->resident)
                                                <a href="{{ route('admin.residents.edit', $gad->resident) }}" class="dropdown-item">
                                                    <i class="fe fe-user fe-16 mr-2 text-success"></i>View Resident
                                                </a>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <a href="#" class="dropdown-item text-warning" 
                                               onclick="event.preventDefault(); confirmArchive('{{ $gad->id }}', '{{ $gad->resident->full_name ?? 'this record' }}')">
                                                <i class="fe fe-archive fe-16 mr-2"></i>Archive
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Archive Form -->
                                    <form id="delete-form-{{ $gad->id }}" action="{{ route('admin.gad.destroy', $gad->id) }}" method="POST" style="display: none;">
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
                        Showing {{ $gadRecords->firstItem() ?? 0 }} to {{ $gadRecords->lastItem() ?? 0 }} of {{ $gadRecords->total() }} records
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($gadRecords->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $gadRecords->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            
                            @for($i = 1; $i <= $gadRecords->lastPage(); $i++)
                                <li class="page-item {{ $i == $gadRecords->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $gadRecords->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            @if($gadRecords->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $gadRecords->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" alt="No GAD records found" class="img-fluid mb-3" width="200">
                    <h4>No GAD records found</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            No GAD records match your search criteria. <a href="{{ route('admin.gad.index') }}">Clear search</a>
                        @else
                            There are no GAD records yet. Start by adding a new record.
                        @endif
                    </p>
                    <a href="{{ route('admin.gad.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16 mr-2"></i>Add New GAD Record
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Archive GAD Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive the GAD record for <strong id="recordName"></strong>?</p>
                <p class="text-warning">The record will be moved to the archive and can be restored later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmDelete">Archive</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmArchive(id, name) {
        document.getElementById('recordName').textContent = name;
        document.getElementById('confirmDelete').onclick = function() {
            document.getElementById('delete-form-' + id).submit();
        };
        $('#deleteModal').modal('show');
    }

    function clearAllFilters() {
        document.getElementById('filterForm').reset();
        window.location.href = "{{ route('admin.gad.index') }}";
    }
</script>
@endsection