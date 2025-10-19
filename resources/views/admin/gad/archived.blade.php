@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.gad.index') }}">Gender & Development</a></li>
<li class="breadcrumb-item active" aria-current="page">Archived</li>
@endsection

@section('page-title', 'Archived GAD Records')
@section('page-subtitle', 'View and manage archived Gender and Development records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-archive fe-16 mr-2"></i>Archived GAD Records</h4>
                        <p class="text-muted mb-0">View and restore archived Gender and Development records</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.gad.index') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fe fe-users fe-16 mr-2"></i>Active GAD Records
                        </a>
                        <a href="{{ route('admin.gad.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus fe-16 mr-2"></i>Add New GAD Record
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form action="{{ route('admin.gad.archived') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search archived GAD records by resident name, ID, program..." 
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
                            @if(request()->hasAny(['search', 'program', 'status', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']))
                                <a href="{{ route('admin.gad.archived') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['program', 'status', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter archived GAD records</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Program</label>
                                        <select name="program" class="form-control form-control-sm">
                                            <option value="">All Programs</option>
                                            @foreach($programTypes as $program)
                                                <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">All Statuses</option>
                                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                            <option value="Discontinued" {{ request('status') == 'Discontinued' ? 'selected' : '' }}>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Special Categories</label>
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_pregnant" name="is_pregnant" value="1" {{ request('is_pregnant') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_pregnant">
                                                        <i class="fe fe-heart fe-16 mr-1"></i>Pregnant
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_solo_parent" name="is_solo_parent" value="1" {{ request('is_solo_parent') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_solo_parent">
                                                        <i class="fe fe-user fe-16 mr-1"></i>Solo Parent
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_vaw_case" name="is_vaw_case" value="1" {{ request('is_vaw_case') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_vaw_case">
                                                        <i class="fe fe-alert-triangle fe-16 mr-1"></i>VAW Case
                                                    </label>
                                                </div>
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
                    @if(request()->hasAny(['search', 'program', 'status', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $archivedGadRecords->total() }} results found</span>
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

                @if($archivedGadRecords->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Gender Identity</th>
                                <th>Programs</th>
                                <th>Status</th>
                                <th>Special Status</th>
                                <th>Archived Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedGadRecords as $gad)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm mr-2">
                                                @if($gad->resident && $gad->resident->photo)
                                                    <img src="{{ $gad->resident->photo_url }}" alt="{{ $gad->resident->full_name }}" class="avatar-img rounded-circle opacity-75">
                                                @else
                                                    <div class="avatar-letter rounded-circle bg-secondary">{{ $gad->resident ? substr($gad->resident->first_name, 0, 1) : 'U' }}</div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong class="text-muted">{{ $gad->resident->full_name ?? 'Unknown Resident' }}</strong>
                                                <br><small class="text-muted">ID: {{ $gad->resident->barangay_id ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-muted">{{ $gad->gender_identity }}</span></td>
                                    <td>
                                        @if($gad->programs_enrolled)
                                            <div class="program-badges">
                                            @foreach($gad->programs_enrolled as $program)
                                                <span class="badge badge-soft-secondary">{{ $program }}</span>
                                            @endforeach
                                            </div>
                                        @else
                                            <span class="badge badge-soft-secondary">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gad->program_status)
                                            <span class="badge badge-soft-secondary">{{ $gad->program_status }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gad->is_pregnant)
                                            <span class="badge badge-soft-secondary">Pregnant</span>
                                        @endif
                                        @if($gad->is_solo_parent)
                                            <span class="badge badge-soft-secondary">Solo Parent</span>
                                        @endif
                                        @if($gad->is_vaw_case)
                                            <span class="badge badge-soft-secondary">VAW Case</span>
                                        @endif
                                        @if(!$gad->is_pregnant && !$gad->is_solo_parent && !$gad->is_vaw_case)
                                            <span class="badge badge-soft-secondary">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $gad->deleted_at->format('M d, Y') }}</small>
                                        <br><small class="text-muted">{{ $gad->deleted_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $dropdownItems = [];
                                            $dropdownItems[] = [
                                                'label' => 'Restore',
                                                'icon' => 'fe fe-rotate-ccw fe-16 text-success',
                                                'class' => '',
                                                'attrs' => "onclick=\"event.preventDefault(); confirmRestore('{$gad->id}', '" . ($gad->resident ? $gad->resident->full_name : 'this record') . "')\" href='#'",
                                            ];
                                            $dropdownItems[] = ['divider' => true];
                                            $dropdownItems[] = [
                                                'label' => 'Delete Permanently',
                                                'icon' => 'fe fe-trash-2 fe-16 text-danger',
                                                'class' => '',
                                                'attrs' => "onclick=\"event.preventDefault(); confirmPermanentDelete('{$gad->id}', '" . ($gad->resident ? $gad->resident->full_name : 'this record') . "')\" href='#'",
                                            ];
                                        @endphp
                                        @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                        <form id="restore-form-{{ $gad->id }}" action="{{ route('admin.gad.restore', $gad->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="permanent-delete-form-{{ $gad->id }}" action="{{ route('admin.gad.force-delete', $gad->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination moved outside table-responsive -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $archivedGadRecords->firstItem() ?? 0 }} to {{ $archivedGadRecords->lastItem() ?? 0 }} of {{ $archivedGadRecords->total() }} archived records
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($archivedGadRecords->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $archivedGadRecords->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            @for($i = 1; $i <= $archivedGadRecords->lastPage(); $i++)
                                <li class="page-item {{ $i == $archivedGadRecords->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $archivedGadRecords->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            @if($archivedGadRecords->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $archivedGadRecords->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="text-center py-5" id="gadArchivedNoResults">
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
                    <h4>No archived GAD records found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['program', 'status', 'is_pregnant', 'is_solo_parent', 'is_vaw_case']))
                            No archived records match your filter criteria. <a href="{{ route('admin.gad.archived') }}">Clear all filters</a>
                        @else
                            There are no archived GAD records at the moment.
                        @endif
                    </p>
                    <a href="{{ route('admin.gad.index') }}" class="btn btn-primary">
                        <i class="fe fe-users fe-16 mr-2"></i>View Active GAD Records
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
                <h5 class="modal-title" id="restoreModalLabel">Restore GAD Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore the GAD record for <strong id="restoreRecordName"></strong>?</p>
                <p class="text-success">This will move the record back to the active list.</p>
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
                <h5 class="modal-title" id="permanentDeleteModalLabel">Permanently Delete GAD Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete the GAD record for <strong id="permanentDeleteRecordName"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="fe fe-alert-triangle fe-16 mr-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All data related to this GAD record will be permanently removed from the system.
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
        document.getElementById('restoreRecordName').textContent = name;
        document.getElementById('confirmRestore').onclick = function() {
            document.getElementById('restore-form-' + id).submit();
        };
        $('#restoreModal').modal('show');
    }

    function confirmPermanentDelete(id, name) {
        document.getElementById('permanentDeleteRecordName').textContent = name;
        document.getElementById('confirmPermanentDelete').onclick = function() {
            document.getElementById('permanent-delete-form-' + id).submit();
        };
        $('#permanentDeleteModal').modal('show');
    }

    function clearAllFilters() {
        document.getElementById('filterForm').reset();
        window.location.href = "{{ route('admin.gad.archived') }}";
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // If any filter is active, scroll to the table
        const urlParams = new URLSearchParams(window.location.search);
        const filterKeys = ['search','program','status','gender_identity','civil_status','age_group','date_from','date_to','is_pregnant','is_solo_parent','is_vaw_case'];
        let hasFilter = false;
        for (const key of filterKeys) {
            if (urlParams.get(key)) {
                hasFilter = true;
                break;
            }
        }
        if (hasFilter) {
            const table = document.querySelector('.table-responsive, table');
            if (table) {
                table.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
</script>
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
.program-badges {
    display: flex;
    flex-direction: row;
    gap: 8px;
    flex-wrap: wrap;
}
.program-badges .badge {
    white-space: normal;
}

</style>
@endpush
@push('scripts')
<script>
    console.log('Auto-scroll script loaded!');
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterKeys = ['search','program','status','gender_identity','civil_status','age_group','date_from','date_to','is_pregnant','is_solo_parent','is_vaw_case'];
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
                    console.log('Scrolling to GAD Archived Table');
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('gadArchivedNoResults');
                    if (noResults) {
                        console.log('Scrolling to GAD Archived No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('GAD Archived Table and No Results not found');
                    }
                }
            }, 400);
        }
    });
</script>
@endpush
