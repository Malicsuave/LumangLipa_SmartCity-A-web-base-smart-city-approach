@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Senior Citizens</li>
@endsection

@section('page-title', 'Senior Citizens Management')
@section('page-subtitle', 'Manage senior citizen residents and their benefits')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-users fe-16 mr-2"></i>Senior Citizens</h4>
                        <p class="text-muted mb-0">Manage senior citizen residents (60 years and above)</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-users fe-16 mr-2"></i>All Residents
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form action="{{ route('admin.senior-citizens.index') }}" method="GET" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, Barangay ID, Senior ID number..." 
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
                            @if(request()->hasAny(['search', 'gender', 'age_range', 'id_status', 'pension_status', 'health_status']))
                                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['gender', 'age_range', 'id_status', 'pension_status', 'health_status']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter senior citizens by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
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
                                        <label class="form-label">Age Range</label>
                                        <select name="age_range" class="form-control form-control-sm">
                                            <option value="">All Ages</option>
                                            <option value="60-69" {{ request('age_range') == '60-69' ? 'selected' : '' }}>60-69</option>
                                            <option value="70-79" {{ request('age_range') == '70-79' ? 'selected' : '' }}>70-79</option>
                                            <option value="80+" {{ request('age_range') == '80+' ? 'selected' : '' }}>80 and above</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">ID Status</label>
                                        <select name="id_status" class="form-control form-control-sm">
                                            <option value="">All Statuses</option>
                                            <option value="issued" {{ request('id_status') == 'issued' ? 'selected' : '' }}>Issued</option>
                                            <option value="not_issued" {{ request('id_status') == 'not_issued' ? 'selected' : '' }}>Not Issued</option>
                                            <option value="needs_renewal" {{ request('id_status') == 'needs_renewal' ? 'selected' : '' }}>Needs Renewal</option>
                                            <option value="expired" {{ request('id_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Pension Status</label>
                                        <select name="pension_status" class="form-control form-control-sm">
                                            <option value="">All</option>
                                            <option value="yes" {{ request('pension_status') == 'yes' ? 'selected' : '' }}>Receiving Pension</option>
                                            <option value="no" {{ request('pension_status') == 'no' ? 'selected' : '' }}>No Pension</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Health Status</label>
                                        <select name="health_status" class="form-control form-control-sm">
                                            <option value="">All</option>
                                            <option value="with_conditions" {{ request('health_status') == 'with_conditions' ? 'selected' : '' }}>With Health Conditions</option>
                                            <option value="no_conditions" {{ request('health_status') == 'no_conditions' ? 'selected' : '' }}>No Health Conditions</option>
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
                    @if(request()->hasAny(['search', 'gender', 'age_range', 'id_status', 'pension_status', 'health_status']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $seniorCitizens->total() }} results found</span>
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('gender'))
                                    <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                        Gender: {{ request('gender') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('age_range'))
                                    <a href="{{ request()->fullUrlWithQuery(['age_range' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        Age Range: {{ request('age_range') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('id_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['id_status' => null]) }}" class="badge badge-secondary mr-1 text-decoration-none">
                                        ID Status: {{ ucfirst(str_replace('_', ' ', request('id_status'))) }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('pension_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['pension_status' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                        Pension: {{ request('pension_status') == 'yes' ? 'Receiving' : 'Not Receiving' }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('health_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['health_status' => null]) }}" class="badge badge-warning mr-1 text-decoration-none">
                                        Health: {{ request('health_status') == 'with_conditions' ? 'With Conditions' : 'No Conditions' }} <i class="fe fe-x"></i>
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

                @if($seniorCitizens->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-hover">
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
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'senior_id_status', 'direction' => request('sort') == 'senior_id_status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                        Senior ID Status
                                        @if(request('sort') == 'senior_id_status')
                                            <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Health Info</th>
                                <th>Benefits</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seniorCitizens as $senior)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
                            <tr>
                                <td><strong>{{ $senior->resident->barangay_id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($senior->resident->photo)
                                                <img src="{{ $senior->resident->photo_url }}" alt="{{ $senior->resident->full_name }}" class="avatar-img rounded-circle">
                                            @else
                                                <div class="avatar-letter rounded-circle bg-warning">{{ substr($senior->resident->first_name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $senior->resident->last_name }}, {{ $senior->resident->first_name }}</strong>
                                            @if($senior->resident->middle_name)
                                                {{ substr($senior->resident->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $senior->resident->suffix }}
                                            @if($senior->senior_id_number)
                                                <br><small class="text-muted">Senior ID: {{ $senior->senior_id_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($senior->resident->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $senior->resident->sex }}</small>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'issued' => 'success',
                                            'not_issued' => 'warning',
                                            'needs_renewal' => 'info',
                                            'expired' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$senior->senior_id_status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-soft-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $senior->senior_id_status)) }}</span>
                                    @if($senior->senior_id_expires_at)
                                        <br><small class="text-muted">Expires: {{ $senior->senior_id_expires_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($senior->health_conditions)
                                        <span class="badge badge-soft-info" title="{{ $senior->health_conditions }}">Has Conditions</span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                    @if($senior->blood_type)
                                        <br><small class="text-muted">Blood: {{ $senior->blood_type }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($senior->receiving_pension)
                                        <span class="badge badge-soft-success">Pension</span>
                                        @if($senior->pension_type)
                                            <br><small class="text-muted">{{ $senior->pension_type }}</small>
                                        @endif
                                    @endif
                                    @if($senior->has_philhealth)
                                        <span class="badge badge-soft-primary">PhilHealth</span>
                                    @endif
                                    @if($senior->has_senior_discount_card)
                                        <span class="badge badge-soft-info">Discount Card</span>
                                    @endif
                                    @if(!$senior->receiving_pension && !$senior->has_philhealth && !$senior->has_senior_discount_card)
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $dropdownItems = [];
                                        $dropdownItems[] = [
                                            'label' => 'Update',
                                            'icon' => 'fe fe-edit-3 fe-16 text-primary',
                                            'href' => route('admin.senior-citizens.edit', $senior->id),
                                        ];
                                        $dropdownItems[] = [
                                            'label' => 'ID Management',
                                            'icon' => 'fe fe-camera fe-16 text-warning',
                                            'href' => route('admin.senior-citizens.id-management', $senior),
                                        ];
                                        if($senior->senior_id_status === 'issued') {
                                            $dropdownItems[] = [
                                                'label' => 'Preview Senior ID',
                                                'icon' => 'fe fe-image fe-16 text-info',
                                                'href' => route('admin.senior-citizens.id.preview', $senior),
                                            ];
                                            $dropdownItems[] = [
                                                'label' => 'Download Senior ID',
                                                'icon' => 'fe fe-download fe-16 text-success',
                                                'href' => route('admin.senior-citizens.id.download', $senior),
                                            ];
                                        } else {
                                            $dropdownItems[] = [
                                                'label' => 'Issue Senior ID',
                                                'icon' => 'fe fe-credit-card fe-16 text-warning',
                                                'attrs' => "onclick=\"event.preventDefault(); document.getElementById('issue-id-form-{$senior->id}').submit();\"",
                                                'href' => '#',
                                            ];
                                        }
                                        $dropdownItems[] = ['divider' => true];
                                    @endphp
                                    @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                    @if($senior->senior_id_status !== 'issued')
                                        <form id="issue-id-form-{{ $senior->id }}" action="{{ route('admin.senior-citizens.issue-id', $senior) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $seniorCitizens->firstItem() ?? 0 }} to {{ $seniorCitizens->lastItem() ?? 0 }} of {{ $seniorCitizens->total() }} senior citizens
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($seniorCitizens->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $seniorCitizens->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            
                            @for($i = 1; $i <= $seniorCitizens->lastPage(); $i++)
                                <li class="page-item {{ $i == $seniorCitizens->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $seniorCitizens->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            @if($seniorCitizens->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $seniorCitizens->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                
                @else
                <div class="text-center py-5" id="seniorNoResults">
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
                    <h4>No senior citizens found</h4>
                    <p class="text-muted">
                        No senior citizens match your search criteria.
                    </p>
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-primary">
                        <i class="fe fe-users fe-16 mr-2"></i>View All Residents
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function clearAllFilters() {
        document.getElementById('filterForm').reset();
        window.location.href = "{{ route('admin.senior-citizens.index') }}";
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
        
        // Restore scroll position on page load
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
@endsection

@push('scripts')
<script>
    console.log('Auto-scroll script loaded!');
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterKeys = ['search','gender','age_range','id_status','pension_status','health_status'];
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
                    console.log('Scrolling to Senior Citizens Table');
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('seniorNoResults');
                    if (noResults) {
                        console.log('Scrolling to Senior Citizens No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('Senior Citizens Table and No Results not found');
                    }
                }
            }, 400);
        }
    });
</script>
@endpush

@push('styles')
<style>
.table-responsive,
.card-body,
.collapse,
#filterSection {
    overflow: visible !important;
}
.dropdown-menu {
    z-index: 9999 !important;
}
.table-responsive {
    padding-bottom: 120px;
}
/* Filter Button Hover Effects */
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
</style>
@endpush