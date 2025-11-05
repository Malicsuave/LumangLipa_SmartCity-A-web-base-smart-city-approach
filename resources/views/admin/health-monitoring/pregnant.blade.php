@extends('layouts.admin.master')

@section('title', 'Pregnant Women Health Monitoring')

@push('styles')
<style>
.trimester-badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
.due-soon {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}
.overdue {
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
                    <i class="fas fa-baby me-2"></i>Pregnant Women Health Monitoring
                </h1>
                <p class="text-muted mb-0">Track prenatal care and maternal health</p>
            </div>
            <div>
                <a href="{{ route('admin.health-monitoring.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">1st Trimester</h6>
                <h3>{{ $pregnant->where('trimester', 1)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">2nd Trimester</h6>
                <h3>{{ $pregnant->where('trimester', 2)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">3rd Trimester</h6>
                <h3>{{ $pregnant->where('trimester', 3)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                <h6 class="text-white-50">Overdue Visits</h6>
                <h3>{{ $pregnant->filter(fn($r) => $r->prenatal_overdue)->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.health-monitoring.pregnant') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by name or Barangay ID...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter by Trimester</label>
                        <select class="form-select" name="trimester">
                            <option value="">All Trimesters</option>
                            <option value="1" {{ request('trimester') == '1' ? 'selected' : '' }}>1st Trimester</option>
                            <option value="2" {{ request('trimester') == '2' ? 'selected' : '' }}>2nd Trimester</option>
                            <option value="3" {{ request('trimester') == '3' ? 'selected' : '' }}>3rd Trimester</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.health-monitoring.pregnant') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pregnant Women List -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Pregnant Women ({{ $pregnant->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($pregnant->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Trimester</th>
                                <th>Expected Due Date</th>
                                <th>Prenatal Visits</th>
                                <th>Last Visit</th>
                                <th>Next Visit</th>
                                <th>Health Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pregnant as $record)
                            @php
                                $daysUntilDue = $record->expected_due_date ? now()->diffInDays($record->expected_due_date, false) : null;
                                $dueSoon = $daysUntilDue !== null && $daysUntilDue <= 30 && $daysUntilDue > 0;
                            @endphp
                            <tr class="{{ $record->prenatal_overdue ? 'overdue' : ($dueSoon ? 'due-soon' : '') }}">
                                <td>
                                    <strong>{{ $record->resident->full_name ?? 'Unknown' }}</strong><br>
                                    <small class="text-muted">{{ $record->barangay_id }}</small>
                                </td>
                                <td>
                                    @if($record->trimester)
                                        <span class="badge trimester-badge 
                                            {{ $record->trimester == 1 ? 'badge-info' : ($record->trimester == 2 ? 'badge-primary' : 'badge-warning') }}">
                                            {{ $record->trimester }}{{ $record->trimester == 1 ? 'st' : ($record->trimester == 2 ? 'nd' : 'rd') }} Trimester
                                        </span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->expected_due_date)
                                        <strong>{{ $record->expected_due_date->format('M d, Y') }}</strong>
                                        @if($daysUntilDue !== null)
                                            <br>
                                            <small class="{{ $dueSoon ? 'text-warning fw-bold' : 'text-muted' }}">
                                                @if($daysUntilDue > 0)
                                                    {{ $daysUntilDue }} days to go
                                                    @if($dueSoon) <i class="fas fa-exclamation-triangle"></i> @endif
                                                @elseif($daysUntilDue < 0)
                                                    {{ abs($daysUntilDue) }} days overdue
                                                @else
                                                    Due today!
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ $record->prenatal_visits_count }} visits
                                    </span>
                                </td>
                                <td>
                                    @if($record->last_prenatal_visit)
                                        {{ $record->last_prenatal_visit->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">No visits yet</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->next_prenatal_visit)
                                        <span class="{{ $record->prenatal_overdue ? 'text-danger fw-bold' : '' }}">
                                            {{ $record->next_prenatal_visit->format('M d, Y') }}
                                            @if($record->prenatal_overdue)
                                                <br><small class="text-danger">
                                                    <i class="fas fa-exclamation-circle"></i> OVERDUE
                                                </small>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">Not scheduled</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->blood_pressure)
                                        <small class="d-block">BP: {{ $record->blood_pressure }}</small>
                                    @endif
                                    @if($record->weight)
                                        <small class="d-block">Weight: {{ $record->weight }} kg</small>
                                    @endif
                                    @if($record->is_malnourished)
                                        <span class="badge badge-warning">Malnourished</span>
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
                    {{ $pregnant->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-baby fa-3x text-muted mb-3"></i>
                    <h5>No Pregnant Women Found</h5>
                    <p class="text-muted">
                        @if(request('search') || request('trimester'))
                            No pregnant women match your search criteria.
                        @else
                            No pregnancy records have been created yet.
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
