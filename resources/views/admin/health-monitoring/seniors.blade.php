@extends('layouts.admin.master')

@section('title', 'Senior Citizens Health Monitoring')

@push('styles')
<style>
.health-card {
    transition: all 0.3s ease;
}
.health-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.overdue {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user-clock me-2"></i>Senior Citizens Health Monitoring
                </h1>
                <p class="text-muted mb-0">Track and manage health records for senior citizens (60+ years)</p>
            </div>
            <div>
                <a href="{{ route('admin.health-monitoring.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.health-monitoring.seniors') }}" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by name or Barangay ID...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            <a href="{{ route('admin.health-monitoring.seniors') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Senior Citizens List -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Senior Citizens ({{ $seniors->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($seniors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Age</th>
                                <th>Health Status</th>
                                <th>Last Check-up</th>
                                <th>Next Check-up</th>
                                <th>Medical Conditions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seniors as $record)
                            <tr class="{{ $record->checkup_overdue ? 'overdue' : '' }}">
                                <td>
                                    <strong>{{ $record->resident->full_name ?? 'Unknown' }}</strong><br>
                                    <small class="text-muted">{{ $record->barangay_id }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $record->resident->age ?? 'N/A' }} years
                                    </span>
                                </td>
                                <td>
                                    @if($record->is_malnourished)
                                        <span class="badge badge-warning">Malnourished</span>
                                    @endif
                                    @if($record->blood_pressure)
                                        <small class="d-block">BP: {{ $record->blood_pressure }}</small>
                                    @endif
                                    @if($record->bmi)
                                        <small class="d-block">BMI: {{ $record->bmi }} ({{ $record->bmi_category }})</small>
                                    @endif
                                </td>
                                <td>
                                    @if($record->last_checkup_date)
                                        {{ $record->last_checkup_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">No record</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->next_checkup_date)
                                        <span class="{{ $record->checkup_overdue ? 'text-danger fw-bold' : '' }}">
                                            {{ $record->next_checkup_date->format('M d, Y') }}
                                            @if($record->checkup_overdue)
                                                <i class="fas fa-exclamation-circle"></i>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">Not scheduled</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->medical_conditions)
                                        <small>{{ Str::limit($record->medical_conditions, 50) }}</small>
                                    @else
                                        <span class="text-muted">None recorded</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.health-monitoring.show', $record->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $seniors->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                    <h5>No Senior Citizens Found</h5>
                    <p class="text-muted">
                        @if(request('search'))
                            No senior citizens match your search criteria.
                        @else
                            No senior citizen health records have been created yet.
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
