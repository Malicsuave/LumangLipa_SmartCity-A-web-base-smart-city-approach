@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Health Services</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Health Services Dashboard</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">Welcome to the health services management module.</p>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fe fe-clipboard me-2"></i>
                                    Manage Health Service Requests
                                </h5>
                                <p class="card-text">Review, approve, and schedule health service requests from residents.</p>
                                <a href="/admin/health-services" class="btn btn-light">
                                    <i class="fe fe-arrow-right me-1"></i>
                                    View Requests
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-1">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary metric-icon">
                                            <i class="fe fe-users text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Total Requests</p>
                                        <span class="h3 metric-counter">{{ $totalRequests ?? 0 }}</span>
                                        <span class="small text-muted">All time</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning metric-icon">
                                            <i class="fe fe-clock text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Pending</p>
                                        <span class="h3 metric-counter">{{ $pendingRequests ?? 0 }}</span>
                                        <span class="small text-muted">Awaiting approval</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success metric-icon">
                                            <i class="fe fe-check text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Completed</p>
                                        <span class="h3 metric-counter">{{ $completedRequests ?? 0 }}</span>
                                        <span class="small text-muted">This month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mt-3 mb-4">Recent Health Service Requests</h5>
                        @if(isset($recentRequests) && $recentRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>Resident</th>
                                        <th>Service</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Date Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                    <tr>
                                        <td>{{ $request->resident_name }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $request->service_type)) }}</td>
                                        <td>{!! $request->priority_badge !!}</td>
                                        <td>{!! $request->status_badge !!}</td>
                                        <td>{{ $request->requested_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @else
                        <div class="text-center py-4">
                            <i class="fe fe-heart fe-48 text-muted mb-3"></i>
                            <h6 class="text-muted">No health service requests yet</h6>
                            <p class="text-muted">Health service requests will appear here once residents submit them.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection