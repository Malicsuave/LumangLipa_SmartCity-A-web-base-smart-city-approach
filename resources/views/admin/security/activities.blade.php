@extends('layouts.admin.master')

@section('title', 'User Activity Log')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="h5 page-title">User Activity Log</h2>
        <p class="text-muted">Review and monitor user activities and security events</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.security.dashboard') }}" class="btn btn-primary">
            <i class="fe fe-arrow-left fe-16 mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Search & Filter Form -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.security.activities') }}" class="form-inline">
            <div class="form-group mr-2 mb-2">
                <select name="user_id" class="form-control">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mr-2 mb-2">
                <select name="activity_type" class="form-control">
                    <option value="">All Activities</option>
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mr-2 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">From</span>
                    </div>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
            </div>

            <div class="form-group mr-2 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">To</span>
                    </div>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
            </div>

            <div class="form-group mr-2 mb-2">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="suspiciousOnly" name="suspicious" value="1" {{ request('suspicious') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="suspiciousOnly">Suspicious Only</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-2">
                <i class="fe fe-search fe-16 mr-2"></i> Filter
            </button>

            <a href="{{ route('admin.security.activities') }}" class="btn btn-outline-secondary ml-2 mb-2">
                <i class="fe fe-x fe-16 mr-2"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $stats['total_logins'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Total Logins</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-log-in fe-24 text-primary"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $stats['suspicious_activities'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Suspicious Events</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-alert-triangle fe-24 text-warning"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $stats['today_activities'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Today's Activities</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-calendar fe-24 text-success"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $stats['failed_logins'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Failed Logins</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-x-circle fe-24 text-danger"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity Table -->
<div class="card shadow">
    <div class="card-header">
        <strong class="card-title">User Activity Records</strong>
        @if(count($activities))
            <span class="badge badge-pill badge-primary ml-2">{{ $activities->total() }} results</span>
        @endif
    </div>
    <div class="card-body">
        @if(count($activities))
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Location</th>
                            <th>Device</th>
                            <th>IP Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td>{{ $activity->created_at->format('M d, Y g:i A') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}" class="avatar-img rounded-circle">
                                        </div>
                                        <div>
                                            <strong>{{ $activity->user->name }}</strong>
                                            <div class="small text-muted">{{ $activity->user->role->name ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</td>
                                <td>{{ $activity->location ?: 'Unknown' }}</td>
                                <td>
                                    @if($activity->device_type == 'mobile')
                                        <i class="fe fe-smartphone mr-1"></i>
                                    @elseif($activity->device_type == 'tablet')
                                        <i class="fe fe-tablet mr-1"></i>
                                    @else
                                        <i class="fe fe-monitor mr-1"></i>
                                    @endif
                                    {{ ucfirst($activity->device_type) }}
                                </td>
                                <td>{{ $activity->ip_address }}</td>
                                <td>
                                    @if($activity->is_suspicious)
                                        <span class="badge badge-pill badge-warning">Suspicious</span>
                                    @elseif(str_contains($activity->activity_type, 'failed'))
                                        <span class="badge badge-pill badge-danger">Failed</span>
                                    @elseif($activity->activity_type == 'login')
                                        <span class="badge badge-pill badge-success">Success</span>
                                    @else
                                        <span class="badge badge-pill badge-info">Info</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $activities->appends(request()->except('page'))->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="fe fe-info mr-1"></i> No activity records match your filter criteria.
            </div>
        @endif
    </div>
</div>
@endsection