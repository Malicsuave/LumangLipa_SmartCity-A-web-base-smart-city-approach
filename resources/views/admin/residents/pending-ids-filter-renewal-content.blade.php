{{-- Pending Renewal Filter Form, Active Filters, and Table --}}
<form action="{{ route('admin.residents.id.pending') }}" method="GET" id="renewalFilterForm">
    <input type="hidden" name="tab" value="renewal">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" name="renewal_search" placeholder="Search by name, Barangay ID, phone number..." value="{{ request('renewal_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        <i class="fe fe-search fe-16"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#renewalFilterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                        <i class="fe fe-filter fe-16"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @if(request()->hasAny(['renewal_search', 'renewal_type', 'renewal_gender', 'renewal_age_group']))
                <a href="{{ route('admin.residents.id.pending') }}?tab=renewal" class="btn btn-outline-secondary">
                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                </a>
            @endif
        </div>
    </div>
    <!-- Collapsible Filter Section -->
    <div class="collapse {{ request()->hasAny(['renewal_type', 'renewal_gender', 'renewal_age_group']) ? 'show' : '' }}" id="renewalFilterSection">
        <div class="card border-left-primary mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary">
                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                    <small class="text-muted ml-2">Filter residents by various criteria</small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resident Type</label>
                        <select name="renewal_type" class="form-control form-control-sm">
                            <option value="">All Types</option>
                            <option value="Non-migrant" {{ request('renewal_type') == 'Non-migrant' ? 'selected' : '' }}>Non-migrant</option>
                            <option value="Migrant" {{ request('renewal_type') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                            <option value="Transient" {{ request('renewal_type') == 'Transient' ? 'selected' : '' }}>Transient</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="renewal_gender" class="form-control form-control-sm">
                            <option value="">All Genders</option>
                            <option value="Male" {{ request('renewal_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ request('renewal_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Non-binary" {{ request('renewal_gender') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                            <option value="Transgender" {{ request('renewal_gender') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                            <option value="Other" {{ request('renewal_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Age Group</label>
                        <select name="renewal_age_group" class="form-control form-control-sm">
                            <option value="">All Ages</option>
                            <option value="0-17" {{ request('renewal_age_group') == '0-17' ? 'selected' : '' }}>0-17 (Minor)</option>
                            <option value="18-59" {{ request('renewal_age_group') == '18-59' ? 'selected' : '' }}>18-59 (Adult)</option>
                            <option value="60+" {{ request('renewal_age_group') == '60+' ? 'selected' : '' }}>60+ (Senior)</option>
                        </select>
                    </div>
                </div>
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
    @if(request()->hasAny(['renewal_search', 'renewal_type', 'renewal_gender', 'renewal_age_group']))
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Active filters:</small>
                    <span class="badge badge-info ml-2">{{ $pendingRenewal->total() }} results found</span>
                </div>
                <small class="text-muted">Click on any filter badge to remove it</small>
            </div>
            <div class="mt-2">
                @if(request('renewal_search'))
                    <a href="{{ request()->fullUrlWithQuery(['renewal_search' => null, 'tab' => 'renewal']) }}" class="badge badge-dark mr-1 text-decoration-none">
                        Search: {{ request('renewal_search') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('renewal_type'))
                    <a href="{{ request()->fullUrlWithQuery(['renewal_type' => null, 'tab' => 'renewal']) }}" class="badge badge-primary mr-1 text-decoration-none">
                        Type: {{ request('renewal_type') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('renewal_gender'))
                    <a href="{{ request()->fullUrlWithQuery(['renewal_gender' => null, 'tab' => 'renewal']) }}" class="badge badge-success mr-1 text-decoration-none">
                        Gender: {{ request('renewal_gender') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('renewal_age_group'))
                    <a href="{{ request()->fullUrlWithQuery(['renewal_age_group' => null, 'tab' => 'renewal']) }}" class="badge badge-info mr-1 text-decoration-none">
                        Age Group: {{ request('renewal_age_group') }} <i class="fe fe-x"></i>
                    </a>
                @endif
            </div>
        </div>
    @endif
</form>

{{-- Table for Pending Renewal --}}
@if($pendingRenewal->count() > 0)
    <div class="table-responsive">
        <table class="table table-borderless table-striped" id="pendingRenewalTable">
            @include('admin.residents.partials.pending-ids-table-renewal', ['pendingRenewal' => $pendingRenewal])
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Showing {{ $pendingRenewal->firstItem() ?? 0 }} to {{ $pendingRenewal->lastItem() ?? 0 }} of {{ $pendingRenewal->total() }} pending renewals
        </div>
        <nav aria-label="Table Paging" class="mb-0">
            <ul class="pagination justify-content-end mb-0">
                @if($pendingRenewal->onFirstPage())
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $pendingRenewal->previousPageUrl() }}&tab=renewal"><i class="fe fe-arrow-left"></i> Previous</a></li>
                @endif
                @for($i = 1; $i <= $pendingRenewal->lastPage(); $i++)
                    <li class="page-item {{ $i == $pendingRenewal->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $pendingRenewal->url($i) }}&tab=renewal">{{ $i }}</a>
                    </li>
                @endfor
                @if($pendingRenewal->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $pendingRenewal->nextPageUrl() }}&tab=renewal">Next <i class="fe fe-arrow-right"></i></a></li>
                @else
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                @endif
            </ul>
        </nav>
    </div>
@else
    <div class="text-center py-5" id="pendingRenewalNoResults">
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
        <h4>No pending renewals</h4>
        <p class="text-muted">No resident IDs are currently marked for renewal.</p>
    </div>
@endif 