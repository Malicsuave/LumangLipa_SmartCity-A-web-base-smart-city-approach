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
                <!-- Search and Filter Form -->
                <form action="{{ route('admin.gad.index') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search GAD records by resident name, ID, program..." 
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#filterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                                        <i class="fe fe-filter fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(request()->hasAny(['search', 'program', 'status', 'gender_identity', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']))
                                <a href="{{ route('admin.gad.index') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['program', 'status', 'gender_identity', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter GAD records by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Program</label>
                                        <select name="program" class="form-control form-control-sm">
                                            <option value="">All Programs</option>
                                            <option value="Women's Livelihood" {{ request('program') == "Women's Livelihood" ? 'selected' : '' }}>Women's Livelihood</option>
                                            <option value="Gender Awareness" {{ request('program') == 'Gender Awareness' ? 'selected' : '' }}>Gender Awareness</option>
                                            <option value="VAW Prevention" {{ request('program') == 'VAW Prevention' ? 'selected' : '' }}>VAW Prevention</option>
                                            <option value="Solo Parent Support" {{ request('program') == 'Solo Parent Support' ? 'selected' : '' }}>Solo Parent Support</option>
                                            <option value="Maternal Health" {{ request('program') == 'Maternal Health' ? 'selected' : '' }}>Maternal Health</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">All Statuses</option>
                                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Gender Identity</label>
                                        <select name="gender_identity" class="form-control form-control-sm">
                                            <option value="">All Gender Identities</option>
                                            <option value="Male" {{ request('gender_identity') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ request('gender_identity') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Non-binary" {{ request('gender_identity') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                            <option value="Transgender" {{ request('gender_identity') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                            <option value="Other" {{ request('gender_identity') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Special Categories</label>
                                        <div class="mt-2">
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input" id="is_pregnant" name="is_pregnant" value="1" {{ request('is_pregnant') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_pregnant">Pregnant</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input" id="is_solo_parent" name="is_solo_parent" value="1" {{ request('is_solo_parent') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_solo_parent">Solo Parent</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="is_vaw_case" name="is_vaw_case" value="1" {{ request('is_vaw_case') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_vaw_case">VAW Case</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Actions -->
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['search', 'program', 'status', 'gender_identity', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $gadRecords->total() }} results found</span>
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('program'))
                                    <a href="{{ request()->fullUrlWithQuery(['program' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                        Program: {{ request('program') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('status'))
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                        Status: {{ request('status') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('gender_identity'))
                                    <a href="{{ request()->fullUrlWithQuery(['gender_identity' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        Gender: {{ request('gender_identity') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('is_pregnant'))
                                    <a href="{{ request()->fullUrlWithQuery(['is_pregnant' => null]) }}" class="badge badge-warning mr-1 text-decoration-none">
                                        Pregnant <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('is_solo_parent'))
                                    <a href="{{ request()->fullUrlWithQuery(['is_solo_parent' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        Solo Parent <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('is_vaw_case'))
                                    <a href="{{ request()->fullUrlWithQuery(['is_vaw_case' => null]) }}" class="badge badge-danger mr-1 text-decoration-none">
                                        VAW Case <i class="fe fe-x"></i>
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

                @if($gadRecords->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'resident_name', 'direction' => request('sort') == 'resident_name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Resident
                                        @if(request('sort') == 'resident_name')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'gender_identity', 'direction' => request('sort') == 'gender_identity' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Gender Identity
                                        @if(request('sort') == 'gender_identity')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Programs</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'program_status', 'direction' => request('sort') == 'program_status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Status
                                        @if(request('sort') == 'program_status')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Special Status
                                        @if(request('sort') == 'created_at')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gadRecords as $gad)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
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
                                        <div class="program-badges">
                                        @foreach($gad->programs_enrolled as $program)
                                            <span class="badge badge-soft-info">{{ $program }}</span>
                                        @endforeach
                                        </div>
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
                                    @php
                                        $dropdownItems = [];
                                        $dropdownItems[] = [
                                            'label' => 'View Details',
                                            'icon' => 'fe fe-eye fe-16 text-info',
                                            'href' => route('admin.gad.show', $gad->id),
                                        ];
                                        $dropdownItems[] = [
                                            'label' => 'Edit Record',
                                            'icon' => 'fe fe-edit-3 fe-16 text-primary',
                                            'href' => route('admin.gad.edit', $gad->id),
                                        ];
                                        if($gad->resident) {
                                            $dropdownItems[] = [
                                                'label' => 'View Resident',
                                                'icon' => 'fe fe-user fe-16 text-success',
                                                'href' => route('admin.residents.edit', $gad->resident),
                                            ];
                                        }
                                        $dropdownItems[] = ['divider' => true];
                                        $fullName = $gad->resident ? $gad->resident->full_name : 'this record';
                                        $dropdownItems[] = [
                                            'label' => 'Archive',
                                            'icon' => 'fe fe-archive fe-16 text-warning',
                                            'class' => 'text-warning',
                                            'attrs' => "onclick=\"event.preventDefault(); confirmArchive('{$gad->id}', '{$fullName}')\"",
                                            'href' => '#',
                                        ];
                                    @endphp
                                    @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
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
                <div class="text-center py-5" id="gadNoResults">
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
                    <h4>No GAD records found</h4>
                    <p class="text-muted">
                        No GAD records match your search criteria.
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

    $(document).ready(function() {
        // Store scroll position before sorting
        function storeScrollPosition() {
            sessionStorage.setItem('tableScrollPosition', window.pageYOffset);
        }
        
        // Restore scroll position after page load
        function restoreScrollPosition() {
            const scrollPosition = sessionStorage.getItem('tableScrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('tableScrollPosition');
            }
        }
        
        // Add click handlers to all sortable headers
        $('.table thead a').on('click', function(e) {
            storeScrollPosition();
            // Allow the link to navigate normally
        });
        
        // Restore scroll position on page load (if coming from a sort)
        restoreScrollPosition();
        
        // Alternative: Scroll to table when sorting to maintain context
        if (window.location.search.includes('sort=')) {
            setTimeout(function() {
                const tableElement = document.querySelector('.table-responsive');
                if (tableElement) {
                    tableElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    });
</script>
@push('scripts')
<script>
    console.log('Auto-scroll script loaded!');
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterKeys = ['search','program','status','gender_identity','is_pregnant','is_solo_parent','is_vaw_case'];
        let hasFilter = false;
        for (const key of filterKeys) {
            if (urlParams.get(key)) {
                hasFilter = true;
                break;
            }
        }
        if (hasFilter) {
            setTimeout(function() {
                const table = document.querySelector('.table-responsive table');
                if (table) {
                    console.log('Scrolling to GAD Table');
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('gadNoResults');
                    if (noResults) {
                        console.log('Scrolling to GAD No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('GAD Table and No Results not found');
                    }
                }
            }, 400);
        }
    });
</script>
@endpush
@endsection

@push('styles')
<style>
.filter-btn-hover {
    transition: transform 0.2s ease-in-out;
}
.filter-btn-hover:hover {
    transform: scale(1.1);
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
}
.filter-btn-hover:focus {
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
    box-shadow: none !important;
}
.table-responsive {
    transition: all 0.3s ease;
}
.table thead a {
    transition: all 0.2s ease;
}
.table thead a:hover {
    transform: translateY(-1px);
}
.program-badges {
    display: flex;
    flex-direction: row;
    gap: 8px;
    flex-wrap: wrap;
}
.program-badges .badge {
    white-space: normal;
}
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
@endpush