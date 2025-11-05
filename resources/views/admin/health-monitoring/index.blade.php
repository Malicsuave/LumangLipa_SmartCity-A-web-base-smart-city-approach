@extends('layouts.admin.master')

@section('title', 'Health Monitoring Dashboard')

@push('styles')
<style>
.stat-card {
    border-radius: 8px;
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}
.overdue-badge {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Health Monitoring Dashboard</h1>
                <p class="text-muted mb-0">Monitor and manage resident health status</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.health-monitoring.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Health Record
                </a>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-export me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.health-monitoring.export', ['type' => 'all']) }}">
                            <i class="fas fa-users me-2"></i>All Records
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.health-monitoring.export', ['type' => 'seniors']) }}">
                            <i class="fas fa-user-clock me-2"></i>Senior Citizens
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.health-monitoring.export', ['type' => 'pregnant']) }}">
                            <i class="fas fa-baby me-2"></i>Pregnant Women
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.health-monitoring.export', ['type' => 'malnourished']) }}">
                            <i class="fas fa-heartbeat me-2"></i>Malnourished
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($category) && $category)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-info alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
            <div>
                <i class="fas fa-filter me-2"></i>
                <strong>Filtered View:</strong>
                @if($category === 'senior')
                    Showing Senior Citizens (60+ years old)
                @elseif($category === 'pregnant')
                    Showing Pregnant Women
                @elseif($category === 'malnourished')
                    Showing Malnourished Cases (BMI < 18.5)
                @endif
            </div>
            <a href="{{ route('admin.health-monitoring.index') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-times me-1"></i>Clear Filter
            </a>
        </div>
    </div>
</div>
@endif

<!-- Demographics Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-primary text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Senior Citizens</div>
                        <div class="h2 mb-0">{{ $stats['total_seniors'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
                <a href="{{ route('admin.health-monitoring.seniors') }}" class="text-white small mt-2 d-block">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-info text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Pregnant Women</div>
                        <div class="h2 mb-0">{{ $stats['total_pregnant'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                </div>
                <a href="{{ route('admin.health-monitoring.pregnant') }}" class="text-white small mt-2 d-block">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-warning text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Malnourished Cases</div>
                        <div class="h2 mb-0">{{ $stats['total_malnourished'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <a href="{{ route('admin.health-monitoring.malnourished') }}" class="text-white small mt-2 d-block">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-success text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Family Planning</div>
                        <div class="h2 mb-0">{{ $stats['total_family_planning'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service Statistics This Month -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Health Services This Month</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-syringe fa-2x text-primary mb-2"></i>
                            <h4>{{ $stats['immunizations_this_month'] ?? 0 }}</h4>
                            <p class="text-muted mb-0">Immunizations</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-stethoscope fa-2x text-success mb-2"></i>
                            <h4>{{ $stats['checkups_this_month'] ?? 0 }}</h4>
                            <p class="text-muted mb-0">Check-ups</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-heartbeat fa-2x text-info mb-2"></i>
                            <h4>{{ $stats['prenatal_this_month'] ?? 0 }}</h4>
                            <p class="text-muted mb-0">Prenatal Visits</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-hands-helping fa-2x text-warning mb-2"></i>
                            <h4>{{ $stats['family_planning_this_month'] ?? 0 }}</h4>
                            <p class="text-muted mb-0">Family Planning</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts and Overdue Items -->
<div class="row mb-4">
    @if(($stats['overdue_checkups'] ?? 0) > 0)
    <div class="col-md-6">
        <div class="card border-warning shadow">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle me-2 overdue-badge"></i>
                    Overdue Check-ups
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>{{ $stats['overdue_checkups'] }}</strong> residents have overdue check-ups
                </p>
                @if(isset($overdueCheckups) && $overdueCheckups->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($overdueCheckups->take(5) as $record)
                        <li class="mb-2">
                            <i class="fas fa-user me-2"></i>
                            {{ $record->resident->full_name ?? 'Unknown' }}
                            <small class="text-muted">
                                - Due: {{ $record->next_checkup_date->format('M d, Y') }}
                            </small>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(($stats['overdue_prenatal'] ?? 0) > 0)
    <div class="col-md-6">
        <div class="card border-danger shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2 overdue-badge"></i>
                    Overdue Prenatal Visits
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>{{ $stats['overdue_prenatal'] }}</strong> pregnant women have overdue prenatal visits
                </p>
                @if(isset($overduePrenatal) && $overduePrenatal->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($overduePrenatal->take(5) as $record)
                        <li class="mb-2">
                            <i class="fas fa-user me-2"></i>
                            {{ $record->resident->full_name ?? 'Unknown' }}
                            <small class="text-muted">
                                - Due: {{ $record->next_prenatal_visit->format('M d, Y') }}
                                (Trimester {{ $record->trimester }})
                            </small>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Recent Health Records -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Health Records</h5>
            </div>
            <div class="card-body">
                @if(isset($recentRecords) && $recentRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Demographics</th>
                                <th>Last Check-up</th>
                                <th>Next Check-up</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRecords as $record)
                            <tr>
                                <td>
                                    <strong>{{ $record->resident->full_name ?? 'Unknown' }}</strong><br>
                                    <small class="text-muted">{{ $record->barangay_id }}</small>
                                </td>
                                <td>
                                    @if($record->is_senior_citizen)
                                        <span class="badge badge-primary">Senior</span>
                                    @endif
                                    @if($record->is_pregnant)
                                        <span class="badge badge-info">Pregnant</span>
                                    @endif
                                    @if($record->is_malnourished)
                                        <span class="badge badge-warning">Malnourished</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $record->last_checkup_date ? $record->last_checkup_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td>
                                    @if($record->next_checkup_date)
                                        <span class="{{ $record->checkup_overdue ? 'text-danger' : '' }}">
                                            {{ $record->next_checkup_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        N/A
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
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No health records found</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
