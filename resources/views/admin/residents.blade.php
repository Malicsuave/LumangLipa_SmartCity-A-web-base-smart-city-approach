@extends('layouts.admin.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-common.css') }}">
<style>

.dropdown-menu {
  transition: all 0.2s ease;
}
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

.table-responsive {
    position: static !important;
}
.dropdown-menu {
    z-index: 1050 !important;
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

<script>
// Archive functions - defined early so they're available for onclick attributes
function handleArchiveClick(event, id, name) {
    event.preventDefault();
    event.stopPropagation();
    
    // Close the dropdown first
    window.dispatchEvent(new CustomEvent('close-all-dropdowns', { detail: { except: null } }));
    
    // Small delay to ensure dropdown closes first
    setTimeout(() => {
        confirmArchive(id, name);
    }, 100);
}

function closeArchiveModal() {
    const modalElement = document.getElementById('archiveResidentModal');
    if (modalElement) {
        if (typeof $.fn.modal !== 'undefined') {
            $('#archiveResidentModal').modal('hide');
        } else {
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remove backdrop
            const backdrop = document.getElementById('modalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }
}

function confirmArchive(id, name) {
    // Check if modal element exists
    const modalElement = document.getElementById('archiveResidentModal');
    
    document.getElementById('residentName').textContent = name;
    document.getElementById('confirmDelete').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    window.dispatchEvent(new CustomEvent('close-all-dropdowns', { detail: { except: null } }));
    
    // Try multiple approaches to show the modal
    if (modalElement) {
        // Approach 1: Try Bootstrap 4 modal
        if (typeof $.fn.modal !== 'undefined') {
            $('#archiveResidentModal').modal('show');
        } else {
            // Approach 2: Vanilla JavaScript
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modalBackdrop';
            backdrop.onclick = closeArchiveModal;
            document.body.appendChild(backdrop);
        }
    }
}
</script>
@endpush

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
                <!-- Search and Filter Form -->
                <form action="{{ route('admin.residents.index') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" name="search" 
                                       placeholder="Search residents by name, ID, contact, address..." 
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
                            @if(request()->hasAny(['search', 'type', 'civil_status', 'gender', 'age_group', 'education', 'citizenship', 'id_status', 'population_sector']))
                                <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
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
                                            <option value="Non-binary" {{ request('gender') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                            <option value="Transgender" {{ request('gender') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                            <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
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

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Date From</label>
                                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Date To</label>
                                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
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
                    @if(request()->hasAny(['search', 'type', 'civil_status', 'gender', 'age_group', 'education', 'citizenship', 'id_status', 'population_sector']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $residents->total() }} results found</span>
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
                    <table class="table table-borderless table-striped table-hover" id="residentTable">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'barangay_id', 'direction' => request('sort') == 'barangay_id' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Barangay ID
                                        @if(request('sort') == 'barangay_id')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_name', 'direction' => request('sort') == 'last_name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Name
                                        @if(request('sort') == 'last_name')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'type_of_resident', 'direction' => request('sort') == 'type_of_resident' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Type
                                        @if(request('sort') == 'type_of_resident')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'birthdate', 'direction' => request('sort') == 'birthdate' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Age/Gender
                                        @if(request('sort') == 'birthdate')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'civil_status', 'direction' => request('sort') == 'civil_status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Civil Status
                                        @if(request('sort') == 'civil_status')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'contact_number', 'direction' => request('sort') == 'contact_number' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Contact
                                        @if(request('sort') == 'contact_number')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($residents as $resident)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                    $dropdownItems = [];
                                    $dropdownItems[] = [
                                        'label' => 'Update',
                                        'icon' => 'fe fe-edit-3 fe-16 text-primary',
                                        'class' => '',
                                        'href' => route('admin.residents.edit', $resident),
                                    ];
                                    $dropdownItems[] = [
                                        'label' => 'Services & Documents',
                                        'icon' => 'fe fe-clipboard fe-16 text-success',
                                        'class' => '',
                                        'href' => route('admin.residents.services', $resident),
                                    ];
                                    $dropdownItems[] = ['divider' => true];
                                    $dropdownItems[] = [
                                        'label' => 'Archive',
                                        'icon' => 'fe fe-archive fe-16 text-warning',
                                        'class' => 'archive-resident',
                                        'attrs' => 'data-id="' . $resident->id . '" data-name="' . htmlspecialchars($resident->first_name . ' ' . $resident->last_name) . '" onclick="handleArchiveClick(event, ' . $resident->id . ', \'' . addslashes(htmlspecialchars($resident->first_name . ' ' . $resident->last_name)) . '\')"',
                                    ];
                                @endphp
                                <tr>
                                    <td><strong>{{ $resident->barangay_id }}</strong></td>
                                    <td><strong>{{ $resident->last_name }}, {{ $resident->first_name }}{{ $resident->middle_name ? ' ' . $resident->middle_name : '' }}{{ $resident->suffix ? ' ' . $resident->suffix : '' }}</strong></td>
                                    <td>{{ $resident->type_of_resident }}</td>
                                    <td>{{ \Carbon\Carbon::parse($resident->birthdate)->age }}<br><small class="text-muted">{{ $resident->sex }}</small></td>
                                    <td>{{ $resident->civil_status }}</td>
                                    <td>{{ $resident->contact_number }}</td>
                                    <td class="text-center table-actions-col">
                                        @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                    </td>
                                </tr>
                                <!-- Delete Form -->
                                <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
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
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $residents->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            @for($i = 1; $i <= $residents->lastPage(); $i++)
                                <li class="page-item {{ $i == $residents->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $residents->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            @if($residents->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $residents->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="text-center py-5" id="residentsNoResults">
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
<div class="modal fade" id="archiveResidentModal" tabindex="-1" role="dialog" aria-labelledby="archiveResidentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveResidentModalLabel">Archive Resident</h5>
                <button type="button" class="close" onclick="closeArchiveModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive the record for <strong id="residentName"></strong>?</p>
                <p class="text-warning">The resident will be moved to the archive and can be restored later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeArchiveModal()">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmDelete">Archive</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Auto-scroll script loaded!');
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterKeys = ['search','type','civil_status','gender','age_group','education','citizenship','id_status','population_sector','date_from','date_to'];
        let hasFilter = false;
        for (const key of filterKeys) {
            if (urlParams.get(key)) {
                hasFilter = true;
                break;
            }
        }
        if (hasFilter) {
            setTimeout(function() {
                const table = document.getElementById('residentTable');
                if (table) {
                    console.log('Scrolling to Residents Table');
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('residentsNoResults');
                    if (noResults) {
                        console.log('Scrolling to Residents No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('Residents Table and No Results not found');
                    }
                }
            }, 400);
        }
    });
</script>
@endpush
