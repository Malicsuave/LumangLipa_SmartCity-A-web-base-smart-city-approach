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
                <!-- Search Bar -->
                <form action="{{ route('admin.senior-citizens.index') }}" method="GET" id="searchForm">
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search senior citizens by name, ID, contact..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            @if(request('search'))
                                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-outline-secondary w-100">
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

                @if($seniorCitizens->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Age/Gender</th>
                                <th>Senior ID Status</th>
                                <th>Health Info</th>
                                <th>Benefits</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seniorCitizens as $senior)
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
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted sr-only">Action</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('admin.senior-citizens.edit', $senior->id) }}" class="dropdown-item">
                                                <i class="fe fe-edit-3 fe-16 mr-2 text-primary"></i>Update
                                            </a>
                                            <a href="{{ route('admin.senior-citizens.id-management', $senior) }}" class="dropdown-item">
                                                <i class="fe fe-camera fe-16 mr-2 text-warning"></i>ID Management
                                            </a>
                                            @if($senior->senior_id_status === 'issued')
                                                <a href="{{ route('admin.senior-citizens.id.preview', $senior) }}" class="dropdown-item">
                                                    <i class="fe fe-image fe-16 mr-2 text-info"></i>Preview Senior ID
                                                </a>
                                                <a href="{{ route('admin.senior-citizens.id.download', $senior) }}" class="dropdown-item">
                                                    <i class="fe fe-download fe-16 mr-2 text-success"></i>Download Senior ID
                                                </a>
                                            @else
                                                <a href="{{ route('admin.senior-citizens.issue-id', $senior) }}" class="dropdown-item" 
                                                   onclick="event.preventDefault(); document.getElementById('issue-id-form-{{ $senior->id }}').submit();">
                                                    <i class="fe fe-credit-card fe-16 mr-2 text-warning"></i>Issue Senior ID
                                                </a>
                                                <form id="issue-id-form-{{ $senior->id }}" action="{{ route('admin.senior-citizens.issue-id', $senior) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                        </div>
                                    </div>
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
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" alt="No senior citizens found" class="img-fluid mb-3" width="200">
                    <h4>No senior citizens found</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            No senior citizens match your search criteria. <a href="{{ route('admin.senior-citizens.index') }}">Clear search</a>
                        @else
                            There are no registered senior citizens at the moment.
                        @endif
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
    $(document).ready(function() {
        // Any additional JavaScript for senior citizens management
    });
</script>
@endsection