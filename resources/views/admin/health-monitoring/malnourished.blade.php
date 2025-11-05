@extends('layouts.admin.master')

@section('title', 'Malnutrition Cases Monitoring')

@push('styles')
<style>
.nutrition-card {
    transition: all 0.3s ease;
}
.nutrition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.critical {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-exclamation-triangle me-2"></i>Malnutrition Cases Monitoring
                </h1>
                <p class="text-muted mb-0">Track and manage malnutrition cases in the barangay</p>
            </div>
            <div>
                <a href="{{ route('admin.health-monitoring.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics by Type -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">Underweight</h6>
                <h3>{{ $malnourished->where('malnutrition_type', 'underweight')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">Stunted</h6>
                <h3>{{ $malnourished->where('malnutrition_type', 'stunted')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">Wasted</h6>
                <h3>{{ $malnourished->where('malnutrition_type', 'wasted')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">Overweight</h6>
                <h3>{{ $malnourished->where('malnutrition_type', 'overweight')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.health-monitoring.malnourished') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by name or Barangay ID...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter by Type</label>
                        <select class="form-select" name="type">
                            <option value="">All Types</option>
                            <option value="underweight" {{ request('type') == 'underweight' ? 'selected' : '' }}>Underweight</option>
                            <option value="stunted" {{ request('type') == 'stunted' ? 'selected' : '' }}>Stunted</option>
                            <option value="wasted" {{ request('type') == 'wasted' ? 'selected' : '' }}>Wasted</option>
                            <option value="overweight" {{ request('type') == 'overweight' ? 'selected' : '' }}>Overweight</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.health-monitoring.malnourished') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Malnutrition Cases List -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Malnutrition Cases ({{ $malnourished->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($malnourished->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Age</th>
                                <th>Type</th>
                                <th>Measurements</th>
                                <th>BMI Status</th>
                                <th>Last Check-up</th>
                                <th>Other Conditions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($malnourished as $record)
                            @php
                                $isCritical = in_array($record->malnutrition_type, ['stunted', 'wasted']);
                            @endphp
                            <tr class="{{ $isCritical ? 'critical' : '' }}">
                                <td>
                                    <strong>{{ $record->resident->full_name ?? 'Unknown' }}</strong><br>
                                    <small class="text-muted">{{ $record->barangay_id }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $record->resident->age ?? 'N/A' }} years
                                    </span>
                                </td>
                                <td>
                                    @switch($record->malnutrition_type)
                                        @case('underweight')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-arrow-down"></i> Underweight
                                            </span>
                                            @break
                                        @case('stunted')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Stunted
                                            </span>
                                            @break
                                        @case('wasted')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-exclamation-circle"></i> Wasted
                                            </span>
                                            @break
                                        @case('overweight')
                                            <span class="badge badge-primary">
                                                <i class="fas fa-arrow-up"></i> Overweight
                                            </span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">Unknown</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($record->height || $record->weight)
                                        @if($record->height)
                                            <small class="d-block">Height: {{ $record->height }} cm</small>
                                        @endif
                                        @if($record->weight)
                                            <small class="d-block">Weight: {{ $record->weight }} kg</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->bmi)
                                        <strong>{{ $record->bmi }}</strong><br>
                                        <small class="
                                            @if($record->bmi_category == 'Underweight') text-warning
                                            @elseif($record->bmi_category == 'Overweight' || $record->bmi_category == 'Obese') text-danger
                                            @else text-success
                                            @endif
                                        ">
                                            {{ $record->bmi_category }}
                                        </small>
                                    @else
                                        <span class="text-muted">Not calculated</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->last_checkup_date)
                                        {{ $record->last_checkup_date->format('M d, Y') }}<br>
                                        <small class="text-muted">
                                            {{ $record->last_checkup_date->diffForHumans() }}
                                        </small>
                                    @else
                                        <span class="text-muted">No check-up yet</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->is_senior_citizen)
                                        <span class="badge badge-info">Senior</span>
                                    @endif
                                    @if($record->is_pregnant)
                                        <span class="badge badge-warning">Pregnant</span>
                                    @endif
                                    @if($record->medical_conditions)
                                        <small class="d-block text-muted">
                                            {{ Str::limit($record->medical_conditions, 30) }}
                                        </small>
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
                    {{ $malnourished->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                    <h5>No Malnutrition Cases Found</h5>
                    <p class="text-muted">
                        @if(request('search') || request('type'))
                            No cases match your search criteria.
                        @else
                            No malnutrition cases have been recorded yet.
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Information Panel -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-info shadow">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Malnutrition Types Explanation</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="text-warning"><i class="fas fa-arrow-down"></i> Underweight</h6>
                        <p class="small">Below normal weight for age. May indicate inadequate food intake or illness.</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-danger"><i class="fas fa-exclamation-triangle"></i> Stunted</h6>
                        <p class="small">Below normal height for age. Indicates chronic malnutrition.</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-danger"><i class="fas fa-exclamation-circle"></i> Wasted</h6>
                        <p class="small">Below normal weight for height. Indicates acute malnutrition.</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-primary"><i class="fas fa-arrow-up"></i> Overweight</h6>
                        <p class="small">Above normal weight for height. May lead to health complications.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
