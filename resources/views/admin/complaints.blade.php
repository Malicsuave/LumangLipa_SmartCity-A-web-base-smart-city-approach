@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Complaints Dashboard</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Complaints Dashboard</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">Welcome to the complaints management module.</p>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fe fe-list me-2"></i>
                                    Manage Complaints
                                </h5>
                                <p class="card-text">Review, approve, and schedule complaint meetings with residents.</p>
                                <a href="{{ route('admin.complaint-management') }}" class="btn btn-dark">
                                    <i class="fe fe-arrow-right me-2"></i>
                                    View All Complaints
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
                                        <span class="circle circle-sm bg-warning metric-icon">
                                            <i class="fe fe-alert-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Pending</p>
                                        <span class="h3 metric-counter">{{ $pendingComplaints ?? 0 }}</span>
                                        <span class="small text-muted">Complaints</span>
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
                                        <span class="circle circle-sm bg-primary metric-icon">
                                            <i class="fe fe-clipboard text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Total</p>
                                        <span class="h3 metric-counter">{{ $totalComplaints ?? 0 }}</span>
                                        <span class="small text-muted">Complaints</span>
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
                                            <i class="fe fe-check-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Resolved</p>
                                        <span class="h3 metric-counter">{{ $resolvedComplaints ?? 0 }}</span>
                                        <span class="small text-muted">This Month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(isset($recentComplaints) && $recentComplaints->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mt-3 mb-4">Recent Complaints</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Complainant</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Filed Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentComplaints as $complaint)
                                    <tr>
                                        <td>
                                            <span class="badge badge-light">#{{ $complaint->id }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $complaint->complainant_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $complaint->barangay_id }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $complaint->formatted_complaint_type }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ Str::limit($complaint->subject, 40) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $complaint->status_badge }}">{{ ucfirst($complaint->status) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $complaint->filed_at->format('M d, Y') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.complaint-management') }}" class="btn btn-outline-primary">
                                <i class="fe fe-eye me-2"></i>
                                View All Complaints
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fe fe-flag fe-48 text-muted mb-3"></i>
                    <h5 class="text-muted">No complaints filed yet</h5>
                    <p class="text-muted">Recent complaints will appear here once residents start filing them.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection