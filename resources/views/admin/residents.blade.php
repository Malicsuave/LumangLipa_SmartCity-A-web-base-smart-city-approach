@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Residents</li>
@endsection

@section('page-title', 'Resident Management')
@section('page-subtitle', 'Manage barangay residents information')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-users fe-16 mr-2"></i>All Residents</h4>
                        <p class="text-muted mb-0">Manage all registered residents in your barangay</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary mr-2" onclick="window.location.href='{{ route('admin.residents.archived') }}'">
                            <i class="fe fe-archive fe-16 mr-2"></i>View Archived
                        </button>
                        <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
                            <i class="fe fe-user-plus fe-16 mr-2"></i>Register New Resident
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route('admin.residents.index') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search residents by name, ID, contact, address..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary w-100" data-toggle="collapse" data-target="#filterSection" aria-expanded="false">
                                <i class="fe fe-filter fe-16 mr-1"></i>Choose Filters
                            </button>
                        </div>
                        <div class="col-md-3">
                            @if(request()->hasAny(['search', 'type', 'civil_status', 'gender', 'age_group', 'education', 'citizenship', 'id_status', 'population_sector']))
                                <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['type', 'civil_status', 'gender', 'age_group', 'education', 'citizenship', 'id_status', 'population_sector']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter residents by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Resident Type</label>
                                        <select name="type" class="form-control form-control-sm">
                                            <option value="">All Types</option>
                                            <option value="Non-migrant" {{ request('type') == 'Non-migrant' ? 'selected' : '' }}>Non-migrant</option>
                                            <option value="Migrant" {{ request('type') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                            <option value="Transient" {{ request('type') == 'Transient' ? 'selected' : '' }}>Transient</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Civil Status</label>
                                        <select name="civil_status" class="form-control form-control-sm">
                                            <option value="">All Status</option>
                                            <option value="Single" {{ request('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ request('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widowed" {{ request('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="Divorced" {{ request('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Separated" {{ request('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-control form-control-sm">
                                            <option value="">All Genders</option>
                                            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Age Group</label>
                                        <select name="age_group" class="form-control form-control-sm">
                                            <option value="">All Ages</option>
                                            <option value="0-17" {{ request('age_group') == '0-17' ? 'selected' : '' }}>0-17 (Minor)</option>
                                            <option value="18-59" {{ request('age_group') == '18-59' ? 'selected' : '' }}>18-59 (Adult)</option>
                                            <option value="60+" {{ request('age_group') == '60+' ? 'selected' : '' }}>60+ (Senior)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Education</label>
                                        <select name="education" class="form-control form-control-sm">
                                            <option value="">All Education Levels</option>
                                            <option value="Elementary" {{ request('education') == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                            <option value="High School" {{ request('education') == 'High School' ? 'selected' : '' }}>High School</option>
                                            <option value="College Graduate" {{ request('education') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                            <option value="Vocational" {{ request('education') == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                            <option value="Post Graduate" {{ request('education') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Citizenship</label>
                                        <select name="citizenship" class="form-control form-control-sm">
                                            <option value="">All Citizenship</option>
                                            <option value="Filipino" {{ request('citizenship') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                            <option value="Dual Citizen" {{ request('citizenship') == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                            <option value="Foreign" {{ request('citizenship') == 'Foreign' ? 'selected' : '' }}>Foreign</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">ID Status</label>
                                        <select name="id_status" class="form-control form-control-sm">
                                            <option value="">All ID Status</option>
                                            <option value="issued" {{ request('id_status') == 'issued' ? 'selected' : '' }}>ID Issued</option>
                                            <option value="not_issued" {{ request('id_status') == 'not_issued' ? 'selected' : '' }}>No ID</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Population Sector</label>
                                        <select name="population_sector" class="form-control form-control-sm">
                                            <option value="">All Sectors</option>
                                            <option value="Senior Citizen" {{ request('population_sector') == 'Senior Citizen' ? 'selected' : '' }}>Senior Citizen</option>
                                            <option value="PWD" {{ request('population_sector') == 'PWD' ? 'selected' : '' }}>PWD</option>
                                            <option value="Indigenous" {{ request('population_sector') == 'Indigenous' ? 'selected' : '' }}>Indigenous</option>
                                            <option value="Solo Parent" {{ request('population_sector') == 'Solo Parent' ? 'selected' : '' }}>Solo Parent</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Filter Actions -->
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary mr-3" onclick="clearAllFilters()">
                                            <i class="fe fe-x fe-16 mr-1"></i>Clear Filters
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['type', 'civil_status', 'gender', 'age_group', 'education', 'citizenship', 'id_status', 'population_sector']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $residents->total() }} results found</span>
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('type'))
                                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                        Type: {{ request('type') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('civil_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['civil_status' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                        Civil Status: {{ request('civil_status') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('gender'))
                                    <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="badge badge-warning mr-1 text-decoration-none">
                                        Gender: {{ request('gender') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('age_group'))
                                    <a href="{{ request()->fullUrlWithQuery(['age_group' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        Age Group: {{ request('age_group') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('education'))
                                    <a href="{{ request()->fullUrlWithQuery(['education' => null]) }}" class="badge badge-secondary mr-1 text-decoration-none">
                                        Education: {{ request('education') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('citizenship'))
                                    <a href="{{ request()->fullUrlWithQuery(['citizenship' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        Citizenship: {{ request('citizenship') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('id_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['id_status' => null]) }}" class="badge badge-light mr-1 text-decoration-none">
                                        ID Status: {{ request('id_status') == 'issued' ? 'ID Issued' : 'No ID' }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('population_sector'))
                                    <a href="{{ request()->fullUrlWithQuery(['population_sector' => null]) }}" class="badge badge-danger mr-1 text-decoration-none">
                                        Sector: {{ request('population_sector') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($residents->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped" id="residentTable">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Age/Gender</th>
                                <th>Civil Status</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($residents as $resident)
                            <tr>
                                <td><strong>{{ $resident->barangay_id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($resident->photo)
                                                <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                            @else
                                                <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                            @if($resident->middle_name)
                                                {{ substr($resident->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $resident->suffix }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info">{{ $resident->type_of_resident }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $resident->sex }}</small>
                                </td>
                                <td>{{ $resident->civil_status }}</td>
                                <td>
                                    <small>{{ $resident->contact_number ?: 'N/A' }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted sr-only">Action</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('admin.residents.edit', $resident) }}" class="dropdown-item">
                                                <i class="fe fe-edit-3 fe-16 mr-2 text-primary"></i>Update
                                            </a>
                                            <a href="{{ route('admin.residents.services', $resident) }}" class="dropdown-item">
                                                <i class="fe fe-clipboard fe-16 mr-2 text-success"></i>Services & Documents
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" class="dropdown-item text-warning" 
                                               onclick="event.preventDefault(); confirmArchive('{{ $resident->id }}', '{{ $resident->first_name }} {{ $resident->last_name }}')">
                                                <i class="fe fe-archive fe-16 mr-2"></i>Archive
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Form -->
                                    <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: none;">
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
                        Showing {{ $residents->firstItem() ?? 0 }} to {{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }} residents
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($residents->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $residents->previousPageUrl() }}">Previous</a></li>
                            @endif
                            
                            @for($i = 1; $i <= $residents->lastPage(); $i++)
                                <li class="page-item {{ $i == $residents->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $residents->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            @if($residents->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $residents->nextPageUrl() }}">Next</a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" alt="No residents found" class="img-fluid mb-3" width="200">
                    <h4>No residents found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'type', 'civil_status', 'gender', 'age_group']))
                            No residents match your search criteria. <a href="{{ route('admin.residents.index') }}">Clear all filters</a>
                        @else
                            There are no residents registered yet. Start by adding a new resident.
                        @endif
                    </p>
                    <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
                        <i class="fe fe-user-plus fe-16 mr-2"></i>Register New Resident
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
                <h5 class="modal-title" id="deleteModalLabel">Archive Resident</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive the record for <strong id="residentName"></strong>?</p>
                <p class="text-warning">The resident will be moved to the archive and can be restored later.</p>
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
        document.getElementById('residentName').textContent = name;
        document.getElementById('confirmDelete').onclick = function() {
            document.getElementById('delete-form-' + id).submit();
        };
        $('#deleteModal').modal('show');
    }

    function clearAllFilters() {
        document.getElementById('filterForm').reset();
        window.location.href = "{{ route('admin.residents.index') }}";
    }
</script>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables for the residents table but disable its pagination
        if ($.fn.DataTable) {
            $('#residentTable').DataTable({
                "paging": false, // Disable DataTables pagination as we're using Laravel's
                "searching": false, // Disable default search as we already have our own
                "ordering": true,
                "info": false, // Hide the "Showing X to Y of Z entries" text since Laravel provides this
                "responsive": true,
                "dom": '<"row"<"col-sm-12"tr>>', // Only show the table without DataTables' pagination controls
                "language": {
                    "emptyTable": "No residents found"
                }
            });
        }
    });
</script>
@endsection