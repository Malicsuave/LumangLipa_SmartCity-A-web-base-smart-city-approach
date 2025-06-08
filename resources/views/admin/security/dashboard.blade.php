@extends('layouts.admin.master')

@section('title', 'Security Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="h5 page-title">Security Page</h2>
        <p class="text-muted">Monitor login activities, account security, and suspicious events</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.security.activities') }}" class="btn btn-primary">
            <i class="fe fe-activity fe-16 mr-2"></i>
            View All Activities
        </a>
    </div>
</div>

<!-- Security metrics -->
<div class="row">
    <!-- Total Logins Card -->
    <div class="col-md-3">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $loginStats['month'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Total Logins</p>
                        <p class="small text-muted mb-0">Last 30 days</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-log-in fe-24 text-primary"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Today's Logins Card -->
    <div class="col-md-3">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $loginStats['today'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Today's Logins</p>
                        <p class="small text-muted mb-0">Last 24 hours</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-calendar fe-24 text-success"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Failed Login Attempts Card -->
    <div class="col-md-3">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h2 mb-0">{{ $failedLoginStats['week'] ?? 0 }}</span>
                        <p class="text-muted mb-0">Failed Attempts</p>
                        <p class="small text-muted mb-0">Last 7 days</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-alert-circle fe-24 text-warning"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 2FA Status Card -->
    <div class="col-md-3">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        @if(isset($twoFactorStats))
                            @php
                                $percentage = $twoFactorStats['total_users'] > 0
                                    ? round(($twoFactorStats['enabled'] / $twoFactorStats['total_users']) * 100)
                                    : 0;
                            @endphp
                            <span class="h2 mb-0">{{ $percentage }}%</span>
                        @else
                            <span class="h2 mb-0">0%</span>
                        @endif
                        <p class="text-muted mb-0">2FA Adoption</p>
                        <p class="small text-muted mb-0">Accounts with 2FA enabled</p>
                    </div>
                    <div class="col-auto">
                        <span class="fe fe-shield fe-24 text-primary"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Activity Chart -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong>Login Activity (Last 14 Days)</strong>
            </div>
            <div class="card-body">
                <div class="chart-holder">
                    <canvas id="loginActivityChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Suspicious Activity List -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong>Suspicious Activities</strong>
            </div>
            <div class="card-body">
                @if(isset($suspiciousActivities) && count($suspiciousActivities) > 0)
                    <div class="list-group list-group-flush my-n3">
                        @foreach($suspiciousActivities as $activity)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="fe fe-alert-triangle fe-24 text-warning"></span>
                                    </div>
                                    <div class="col">
                                        <small><strong>{{ $activity->user->name }}</strong></small>
                                        <div class="my-0 text-muted small">{{ ucfirst($activity->activity_type) }} from new location</div>
                                        <small class="badge badge-pill badge-light text-muted">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-success mb-0">
                        <i class="fe fe-check-circle mr-1"></i> No suspicious activities detected
                    </div>
                @endif
                
                <div class="text-center mt-3">
                    <a href="{{ route('admin.security.activities', ['suspicious' => 1]) }}" class="btn btn-sm btn-secondary">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent User Activities -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong>Recent User Activities</strong>
            </div>
            <div class="card-body">
                @if(isset($recentActivities) && count($recentActivities) > 0)
                    <table class="table table-borderless table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Device</th>
                                <th>IP Address</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->user->name }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</td>
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
                                    <td>{{ $activity->created_at->format('M d, Y g:i A') }}</td>
                                    <td>
                                        @if($activity->is_suspicious)
                                            <span class="badge badge-pill badge-warning">Suspicious</span>
                                        @else
                                            <span class="badge badge-pill badge-success">Normal</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fe fe-info mr-1"></i> No recent activities to display
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up login activity chart
    var ctx = document.getElementById('loginActivityChart');
    
    // Check if we have chart data
    @if(isset($loginChartData) && isset($failedChartData))
        var loginChartData = {!! $loginChartData !!};
        var failedChartData = {!! $failedChartData !!};
        
        var labels = loginChartData.map(function(item) { return item.date; });
        var successData = loginChartData.map(function(item) { return item.count; });
        var failedData = failedChartData.map(function(item) { return item.count; });
        
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Successful Logins',
                    data: successData,
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(52, 152, 219, 1)',
                    tension: 0.4
                }, {
                    label: 'Failed Attempts',
                    data: failedData,
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(231, 76, 60, 1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            }
        });
    @else
        // Display empty chart with message if no data
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['No Data Available'],
                datasets: [{
                    data: [0],
                    backgroundColor: 'rgba(200, 200, 200, 0.2)',
                    borderColor: 'rgba(200, 200, 200, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    @endif
});
</script>
@endpush