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
        <div class="card shadow mb-4">
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
                <!-- Search and Filter Form -->
                <form action="{{ route('admin.residents.archived') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search archived residents by name, ID, contact..." 
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
                            @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'purok']))
                                <a href="{{ route('admin.residents.archived') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['type', 'gender', 'age_group', 'purok']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter archived residents by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Resident Type</label>
                                        <select name="type" class="form-control form-control-sm">
                                            <option value="">All Types</option>
                                            <option value="Permanent" {{ request('type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                            <option value="Temporary" {{ request('type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                            <option value="Boarder" {{ request('type') == 'Boarder' ? 'selected' : '' }}>Boarder</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-control form-control-sm">
                                            <option value="">All Genders</option>
                                            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Non-binary" {{ request('gender') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                            <option value="Transgender" {{ request('gender') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                            <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Age Group</label>
                                        <select name="age_group" class="form-control form-control-sm">
                                            <option value="">All Ages</option>
                                            <option value="0-17" {{ request('age_group') == '0-17' ? 'selected' : '' }}>Child (0-17)</option>
                                            <option value="18-59" {{ request('age_group') == '18-59' ? 'selected' : '' }}>Adult (18-59)</option>
                                            <option value="60+" {{ request('age_group') == '60+' ? 'selected' : '' }}>Senior (60+)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Purok/Zone</label>
                                        <select name="purok" class="form-control form-control-sm">
                                            <option value="">All Puroks</option>
                                            @for($i = 1; $i <= 7; $i++)
                                                <option value="Purok {{ $i }}" {{ request('purok') == "Purok $i" ? 'selected' : '' }}>Purok {{ $i }}</option>
                                            @endfor
                                        </select>
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
                    @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'purok']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $archivedResidents->total() }} results found</span>
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('type'))
                                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                        Type: {{ request('type') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('gender'))
                                    <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                        Gender: {{ request('gender') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('age_group'))
                                    <a href="{{ request()->fullUrlWithQuery(['age_group' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        Age: {{ ucfirst(request('age_group')) }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('purok'))
                                    <a href="{{ request()->fullUrlWithQuery(['purok' => null]) }}" class="badge badge-warning mr-1 text-decoration-none">
                                        {{ request('purok') }} <i class="fe fe-x"></i>
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

                @if($archivedResidents->count())
                <div class="table-responsive">
                    <table id="resultsTable" class="table table-borderless table-striped">
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
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
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
                                        {{ $resident->type_of_resident }}
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
                                        @php
                                            $dropdownItems = [];
                                            $dropdownItems[] = [
                                                'label' => 'Restore',
                                                'icon' => 'fe fe-rotate-ccw fe-16 text-success',
                                                'class' => '',
                                                'attrs' => "onclick=\"event.preventDefault(); confirmRestore('{$resident->id}', '{$resident->first_name} {$resident->last_name}')\" href='#'",
                                            ];
                                            $dropdownItems[] = ['divider' => true];
                                            $dropdownItems[] = [
                                                'label' => 'Delete Permanently',
                                                'icon' => 'fe fe-trash-2 fe-16 text-danger',
                                                'class' => '',
                                                'attrs' => "onclick=\"event.preventDefault(); confirmPermanentDelete('{$resident->id}', '{$resident->first_name} {$resident->last_name}')\" href='#'",
                                            ];
                                        @endphp
                                        @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
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
                <div class="text-center py-5" id="archivedNoResults">
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
                    <h4>No archived residents found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'purok']))
                            No archived residents match your search criteria. <a href="{{ route('admin.residents.archived') }}">Clear search</a>
                        @else
                            There are no archived residents yet.
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

    function clearAllFilters() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('type');
        url.searchParams.delete('gender');
        url.searchParams.delete('age_group');
        url.searchParams.delete('purok');
        window.location.href = url.toString();
    }
</script>
@endsection

@push('styles')
<style>
/* Smooth scroll behavior for the entire page */
html {
    scroll-behavior: smooth;
}

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

/* Smooth transitions for table elements */
.table-responsive {
    transition: all 0.3s ease;
}

/* Enhanced focus and smooth transitions */
.table thead a {
    transition: all 0.2s ease;
}

.table thead a:hover {
    transform: translateY(-1px);
}

.avatar {
    position: relative;
    width: 32px;
    height: 32px;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-letter {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.opacity-75 {
    opacity: 0.75;
}

.badge-soft-secondary {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
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

@push('scripts')
<script>
    console.log('Auto-scroll script loaded!');
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('search') || urlParams.get('type') || urlParams.get('gender') || urlParams.get('age_group') || urlParams.get('purok')) {
            setTimeout(function() {
                const table = document.getElementById('resultsTable');
                if (table) {
                    console.log('Scrolling to Archived Residents Table');
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('archivedNoResults');
                    if (noResults) {
                        console.log('Scrolling to Archived Residents No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('Archived Residents Table and No Results not found');
                    }
                }
            }, 400);
        }
    });
</script>
@endpush