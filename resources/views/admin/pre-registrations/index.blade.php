@extends('layouts.admin.master')

@section('title', 'Pre-Registration Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-common.css') }}">
<style>
/* Smooth scroll behavior for the entire page */
html {
    scroll-behavior: smooth;
}
.dropdown-menu {
  transition: all 0.2s ease;
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

.type-col {
    white-space: nowrap;
    overflow: visible;
    text-overflow: unset;
    max-width: none;
    min-width: 90px;
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

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Pre-Registration Management</h1>
                <p class="text-muted mb-0">Review and approve resident pre-registration applications</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="fe fe-external-link fe-16 mr-2"></i>View Public Form
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-users text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Total Applications</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['total'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning">
                            <i class="fe fe-16 fe-clock text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Pending Review</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['pending'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-success">
                            <i class="fe fe-16 fe-check-circle text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Approved</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['approved'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-danger">
                            <i class="fe fe-16 fe-x-circle text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Rejected</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $stats['rejected'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Pre-Registration Applications</strong>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.pre-registrations.index') }}" id="filterForm">
                    <!-- Search and Filter Toggle -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, email, phone, or address..." 
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
                            @if(request()->hasAny(['search', 'status', 'type_of_resident', 'age_group', 'gender', 'civil_status', 'education', 'date_from', 'date_to']))
                                <a href="{{ route('admin.pre-registrations.index') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['status', 'type_of_resident', 'age_group', 'gender', 'civil_status', 'education', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter pre-registration applications by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">All Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Resident Type</label>
                                        <select name="type_of_resident" class="form-control form-control-sm">
                                            <option value="">All Types</option>
                                            <option value="Non-migrant" {{ request('type_of_resident') == 'Non-migrant' ? 'selected' : '' }}>Non-migrant</option>
                                            <option value="Migrant" {{ request('type_of_resident') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                            <option value="Transient" {{ request('type_of_resident') == 'Transient' ? 'selected' : '' }}>Transient</option>
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
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Civil Status</label>
                                        <select name="civil_status" class="form-control form-control-sm">
                                            <option value="">All Civil Status</option>
                                            <option value="Single" {{ request('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ request('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widowed" {{ request('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="Divorced" {{ request('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Separated" {{ request('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Education Level</label>
                                        <select name="education" class="form-control form-control-sm">
                                            <option value="">All Education Levels</option>
                                            <option value="Elementary" {{ request('education') == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                            <option value="Highschool" {{ request('education') == 'Highschool' ? 'selected' : '' }}>Highschool</option>
                                            <option value="College" {{ request('education') == 'College' ? 'selected' : '' }}>College</option>
                                            <option value="Post Graduate" {{ request('education') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                            <option value="Vocational" {{ request('education') == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                            <option value="not applicable" {{ request('education') == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
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
                    @if(request()->hasAny(['search', 'status', 'type_of_resident', 'age_group', 'gender', 'civil_status', 'education', 'date_from', 'date_to']))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Active filters:</small>
                                    <span class="badge badge-info ml-2">{{ $preRegistrations->total() }} results found</span>
                                </div>
                                <small class="text-muted">Click on any filter badge to remove it</small>
                            </div>
                            <div class="mt-2">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('status'))
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                        Status: {{ ucfirst(request('status')) }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('type_of_resident'))
                                    <a href="{{ request()->fullUrlWithQuery(['type_of_resident' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                        Type: {{ request('type_of_resident') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('age_group'))
                                    <a href="{{ request()->fullUrlWithQuery(['age_group' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                        Age Group: {{ request('age_group') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('gender'))
                                    <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="badge badge-warning mr-1 text-decoration-none">
                                        Gender: {{ request('gender') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('civil_status'))
                                    <a href="{{ request()->fullUrlWithQuery(['civil_status' => null]) }}" class="badge badge-secondary mr-1 text-decoration-none">
                                        Civil Status: {{ request('civil_status') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('education'))
                                    <a href="{{ request()->fullUrlWithQuery(['education' => null]) }}" class="badge badge-light mr-1 text-decoration-none">
                                        Education: {{ request('education') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('date_from'))
                                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null]) }}" class="badge badge-danger mr-1 text-decoration-none">
                                        From: {{ request('date_from') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                                @if(request('date_to'))
                                    <a href="{{ request()->fullUrlWithQuery(['date_to' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                        To: {{ request('date_to') }} <i class="fe fe-x"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
                
                @if($preRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table id="resultsTable" class="table table-borderless table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'direction' => request('sort') == 'first_name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                            Name
                                            @if(request('sort') == 'first_name')
                                                <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @else
                                                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'email_address', 'direction' => request('sort') == 'email_address' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                            Email
                                            @if(request('sort') == 'email_address')
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
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'age', 'direction' => request('sort') == 'age' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                            Age
                                            @if(request('sort') == 'age')
                                                <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @else
                                                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="type-col">
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fe fe-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @else
                                                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                            Submitted
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
                                @foreach($preRegistrations as $registration)
                                    @php
                                        $dropdownItems = [];
                                        $dropdownItems[] = [
                                            'label' => 'View Details',
                                            'icon' => 'fe fe-eye fe-16 text-primary',
                                            'class' => '',
                                            'href' => route('admin.pre-registrations.show', $registration),
                                        ];
                                        if ($registration->status === 'pending') {
                                            $dropdownItems[] = ['divider' => true];
                                            $dropdownItems[] = [
                                                'label' => 'Approve',
                                                'icon' => 'fe fe-check fe-16 text-success',
                                                'class' => '',
                                                'attrs' => 'onclick="approveRegistration(' . $registration->id . ')"',
                                            ];
                                            $dropdownItems[] = [
                                                'label' => 'Reject',
                                                'icon' => 'fe fe-x fe-16 text-warning',
                                                'class' => '',
                                                'attrs' => 'onclick="rejectRegistration(' . $registration->id . ')"',
                                            ];
                                        }
                                        $dropdownItems[] = ['divider' => true];
                                        $dropdownItems[] = [
                                            'label' => 'Delete',
                                            'icon' => 'fe fe-trash-2 fe-16 text-danger',
                                            'class' => 'text-danger',
                                            'attrs' => 'onclick="deleteRegistration(' . $registration->id . ')"',
                                        ];
                                    @endphp
                                    @php
                                        $isLastTwo = $loop->remaining < 2;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $registration->full_name }}</strong>
                                            @if($registration->is_senior_citizen)
                                                <br><small class="badge badge-warning">Senior Citizen</small>
                                            @endif
                                        </td>
                                        <td>{{ $registration->email_address }}</td>
                                        <td>{{ $registration->contact_number }}</td>
                                        <td>{{ $registration->age }}</td>
                                        <td class="type-col">{{ $registration->type_of_resident }}</td>
                                        <td>
                                            @if($registration->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($registration->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $registration->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing {{ $preRegistrations->firstItem() ?? 0 }} to {{ $preRegistrations->lastItem() ?? 0 }} of {{ $preRegistrations->total() }} pre-registrations
                        </div>
                        <nav aria-label="Table Paging" class="mb-0">
                            <ul class="pagination justify-content-end mb-0">
                                    {!! $preRegistrations->appends(request()->query())->links() !!}
                            </ul>
                        </nav>
                    </div>
                @else
                <div class="text-center py-5">
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
                    <h4>No pre-registrations found</h4>
                    <p class="text-muted">
                        No applications match your search criteria. <a href="{{ route('admin.pre-registrations.index') }}">Clear all filters</a>
                    </p>
                    <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-primary" target="_blank">
                        <i class="fe fe-external-link fe-16 mr-2"></i>View Public Registration Form
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this registration?</p>
                <p class="text-info">
                    <i class="fe fe-info"></i> This will create a resident record and send a digital ID to the applicant's email.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" class="admin-inline-form">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fe fe-check-circle fe-16 mr-2 text-white"></i> Approve
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this registration:</p>
                    <textarea class="form-control" name="rejection_reason" rows="3" 
                              placeholder="Enter reason for rejection..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fe fe-x"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this registration?</p>
                <p class="text-danger">
                    <i class="fe fe-alert-triangle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="admin-inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Enhanced smooth scroll functions
function smoothScrollTo(targetY, duration = 800) {
    const startY = window.pageYOffset;
    const difference = targetY - startY;
    const startTime = performance.now();

    function step() {
        const progress = Math.min((performance.now() - startTime) / duration, 1);
        const ease = easeInOutCubic(progress);
        window.scrollTo(0, startY + difference * ease);
        
        if (progress < 1) {
            requestAnimationFrame(step);
        }
    }
    
    requestAnimationFrame(step);
}

function easeInOutCubic(t) {
    return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
}

// Store scroll position before sorting
function storeScrollPosition() {
    sessionStorage.setItem('tableScrollPosition', window.pageYOffset);
}

// Enhanced restore scroll position with smooth animation
function restoreScrollPosition() {
    const scrollPosition = sessionStorage.getItem('tableScrollPosition');
    if (scrollPosition) {
        const targetPosition = parseInt(scrollPosition);
        // Use smooth scroll instead of instant jump
        smoothScrollTo(targetPosition, 600);
        sessionStorage.removeItem('tableScrollPosition');
    }
}

// Enhanced smooth scroll to table
function smoothScrollToTable() {
    const tableElement = document.querySelector('.table-responsive');
    if (tableElement) {
        const targetY = tableElement.getBoundingClientRect().top + window.pageYOffset - 100;
        smoothScrollTo(targetY, 800);
    }
}

// Define functions in global scope
window.approveRegistration = function(id) {
    console.log('Approve function called for ID:', id);
    const form = document.getElementById('approveForm');
    if (form) {
        const newAction = `/admin/pre-registrations/${id}/approve`;
        form.action = newAction;
        
        // Test form submission
        form.addEventListener('submit', function(e) {
            console.log('Form is submitting to:', this.action);
        });
        
        $('#approveModal').modal('show');
    } else {
        console.error('Approve form not found');
    }
};

window.rejectRegistration = function(id) {
    console.log('Reject function called for ID:', id);
    const form = document.getElementById('rejectForm');
    if (form) {
        form.action = `/admin/pre-registrations/${id}/reject`;
        $('#rejectModal').modal('show');
    } else {
        console.error('Reject form not found');
    }
};

window.deleteRegistration = function(id) {
    console.log('Delete function called for ID:', id);
    const form = document.getElementById('deleteForm');
    if (form) {
        form.action = `/admin/pre-registrations/${id}`;
        $('#deleteModal').modal('show');
    } else {
        console.error('Delete form not found');
    }
};

// Initialize when document is ready
$(document).ready(function() {
    console.log('Pre-registration page initialized');
    
    // Add click handlers to all sortable headers with enhanced scroll
    $('.table thead a').on('click', function(e) {
        storeScrollPosition();
        // Add visual feedback
        $(this).addClass('table-sort-loading');
        setTimeout(() => {
            $(this).removeClass('table-sort-loading');
        }, 300);
        // Allow the link to navigate normally
    });
    
    // Enhanced scroll restoration on page load
    setTimeout(() => {
        restoreScrollPosition();
    }, 100);
    
    // Enhanced scroll to table when sorting
    if (window.location.search.includes('sort=')) {
        setTimeout(() => {
            smoothScrollToTable();
        }, 200);
    }
    
    // Enhanced dropdown handling
    $('.btn-icon').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Close all other dropdowns with smooth animation
        $('.dropdown-menu.show').removeClass('show');
        
        // Toggle this dropdown with smooth animation
        const menu = $(this).next('.dropdown-menu');
        menu.toggleClass('show');
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });
    
    // Prevent dropdown from closing when clicking inside
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Enhanced form submission handlers
    $('#approveForm').on('submit', function(e) {
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Form action is not set correctly. Please try clicking approve again.');
            return false;
        }
        
        if (!this.action.includes('/admin/pre-registrations/') || !this.action.includes('/approve')) {
            e.preventDefault();
            alert('Error: Form action is not set correctly. Please try clicking approve again.');
            return false;
        }
    });
    
    $('#rejectForm').on('submit', function(e) {
        const reason = $(this).find('textarea[name="rejection_reason"]').val();
        if (!reason.trim()) {
            e.preventDefault();
            alert('Please provide a reason for rejection.');
            return false;
        }
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Invalid form action. Please try again.');
            return false;
        }
    });
    
    $('#deleteForm').on('submit', function(e) {
        if (!this.action || this.action.includes('undefined')) {
            e.preventDefault();
            alert('Error: Invalid form action. Please try again.');
            return false;
        }
    });

    // Auto-scroll to table after filtering (unified and robust)
    const urlParams = new URLSearchParams(window.location.search);
    const filterKeys = ['search','status','type_of_resident','age_group','gender','civil_status','education','date_from','date_to'];
    let hasFilter = false;
    for (const key of filterKeys) {
        if (urlParams.get(key)) {
            hasFilter = true;
            break;
        }
    }
    if (hasFilter) {
        const table = document.getElementById('resultsTable');
        if (table) {
            table.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});
</script>
@endpush