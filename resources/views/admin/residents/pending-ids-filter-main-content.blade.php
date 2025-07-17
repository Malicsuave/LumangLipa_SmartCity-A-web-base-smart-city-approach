{{-- Pending Issuance Filter Form, Active Filters, and Table --}}
<form action="{{ route('admin.residents.id.pending') }}" method="GET" id="issuanceFilterForm">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by name, Barangay ID, phone number..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        <i class="fe fe-search fe-16"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#issuanceFilterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                        <i class="fe fe-filter fe-16"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo']))
                <a href="{{ route('admin.residents.id.pending') }}?tab=issuance" class="btn btn-outline-secondary">
                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                </a>
            @endif
        </div>
    </div>
    <!-- Collapsible Filter Section -->
    <div class="collapse {{ request()->hasAny(['type', 'gender', 'age_group', 'has_photo']) ? 'show' : '' }}" id="issuanceFilterSection">
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
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Photo Status</label>
                        <select name="has_photo" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="yes" {{ request('has_photo') == 'yes' ? 'selected' : '' }}>With Photo</option>
                            <option value="no" {{ request('has_photo') == 'no' ? 'selected' : '' }}>Without Photo</option>
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
    @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo']))
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Active filters:</small>
                    <span class="badge badge-info ml-2">{{ $pendingIssuance->total() }} results found</span>
                </div>
                <small class="text-muted">Click on any filter badge to remove it</small>
            </div>
            <div class="mt-2">
                @if(request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search' => null, 'tab' => 'issuance']) }}" class="badge badge-dark mr-1 text-decoration-none">
                        Search: {{ request('search') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('type'))
                    <a href="{{ request()->fullUrlWithQuery(['type' => null, 'tab' => 'issuance']) }}" class="badge badge-primary mr-1 text-decoration-none">
                        Type: {{ request('type') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('gender'))
                    <a href="{{ request()->fullUrlWithQuery(['gender' => null, 'tab' => 'issuance']) }}" class="badge badge-success mr-1 text-decoration-none">
                        Gender: {{ request('gender') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('age_group'))
                    <a href="{{ request()->fullUrlWithQuery(['age_group' => null, 'tab' => 'issuance']) }}" class="badge badge-info mr-1 text-decoration-none">
                        Age Group: {{ request('age_group') }} <i class="fe fe-x"></i>
                    </a>
                @endif
                @if(request('has_photo'))
                    <a href="{{ request()->fullUrlWithQuery(['has_photo' => null, 'tab' => 'issuance']) }}" class="badge badge-warning mr-1 text-decoration-none">
                        Photo: {{ request('has_photo') == 'yes' ? 'With Photo' : 'Without Photo' }} <i class="fe fe-x"></i>
                    </a>
                @endif
            </div>
        </div>
    @endif
</form> 