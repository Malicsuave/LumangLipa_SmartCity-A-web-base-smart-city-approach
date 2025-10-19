@extends('layouts.admin.master')

@section('title', 'Activity Details')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="h5 page-title">Activity Details #{{ $activity->id }}</h2>
        <p class="text-muted">Detailed information about this security event</p>
    </div>
    <div class="col-auto">
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            <i class="fe fe-arrow-left fe-16 mr-2"></i>
            Back
        </a>
    </div>
</div>

<div class="row">
    <!-- Activity Details Card -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong>Activity Information</strong>
                <span class="ml-2">
                    @if($activity->is_suspicious)
                        <span class="badge badge-pill badge-warning">Suspicious</span>
                    @elseif(str_contains($activity->activity_type, 'failed'))
                        <span class="badge badge-pill badge-danger">Failed</span>
                    @elseif($activity->activity_type == 'login')
                        <span class="badge badge-pill badge-success">Success</span>
                    @else
                        <span class="badge badge-pill badge-info">Info</span>
                    @endif
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Activity Type:</strong></p>
                        <p>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Date & Time:</strong></p>
                        <p>{{ $activity->created_at->format('F j, Y g:i:s A') }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>IP Address:</strong></p>
                        <p>{{ $activity->ip_address }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Device Type:</strong></p>
                        <p>
                            @if($activity->device_type == 'mobile')
                                <i class="fe fe-smartphone mr-1"></i>
                            @elseif($activity->device_type == 'tablet')
                                <i class="fe fe-tablet mr-1"></i>
                            @else
                                <i class="fe fe-monitor mr-1"></i>
                            @endif
                            {{ ucfirst($activity->device_type) }}
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <p class="mb-1"><strong>User Agent:</strong></p>
                        <p class="text-muted small">{{ $activity->user_agent }}</p>
                    </div>
                </div>
                
                @if($activity->details)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-3">Additional Details</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($activity->details as $key => $value)
                                        <tr>
                                            <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                            <td>
                                                @if(is_bool($value))
                                                    {{ $value ? 'Yes' : 'No' }}
                                                @elseif(is_array($value) || is_object($value))
                                                    <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- User Information Card -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong>User Information</strong>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-lg">
                        <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}" class="avatar-img rounded-circle">
                    </div>
                    <h5 class="mt-3 mb-0">{{ $activity->user->name }}</h5>
                    <p class="text-muted">{{ $activity->user->role->name ?? 'No Role' }}</p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <p class="mb-1"><strong>Email:</strong></p>
                        <p>{{ $activity->user->email }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <p class="mb-1"><strong>User ID:</strong></p>
                        <p>{{ $activity->user->id }}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <p class="mb-1"><strong>2FA Status:</strong></p>
                        @if($activity->user->two_factor_secret)
                            <span class="badge badge-pill badge-success">Enabled</span>
                        @else
                            <span class="badge badge-pill badge-warning">Not Enabled</span>
                        @endif
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('admin.security.activities', ['user_id' => $activity->user->id]) }}" class="btn btn-sm btn-primary">
                        View User's Activities
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Activities -->
@if(isset($relatedActivities) && count($relatedActivities) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong>Related Activities</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Activity</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($relatedActivities as $related)
                                <tr>
                                    <td>{{ $related->created_at->format('M d, Y g:i A') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $related->activity_type)) }}</td>
                                    <td>{{ $related->ip_address }}</td>
                                    <td>
                                        @if($related->is_suspicious)
                                            <span class="badge badge-pill badge-warning">Suspicious</span>
                                        @elseif(str_contains($related->activity_type, 'failed'))
                                            <span class="badge badge-pill badge-danger">Failed</span>
                                        @elseif($related->activity_type == 'login')
                                            <span class="badge badge-pill badge-success">Success</span>
                                        @else
                                            <span class="badge badge-pill badge-info">Info</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.security.activities.show', $related->id) }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection