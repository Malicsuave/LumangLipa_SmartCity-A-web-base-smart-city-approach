@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics Dashboard</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">Barangay Analytics</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">This is the analytics module exclusive to the Barangay Captain.</p>
                
                <div class="row my-4">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <strong>Population Demographics</strong>
                            </div>
                            <div class="card-body">
                                <div class="chart-area" style="height: 300px;">
                                    <!-- Placeholder for chart -->
                                    <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                                        <span class="text-muted">Population Demographics Chart</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <strong>Service Requests</strong>
                            </div>
                            <div class="card-body">
                                <div class="chart-area" style="height: 300px;">
                                    <!-- Placeholder for chart -->
                                    <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                                        <span class="text-muted">Service Requests Chart</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <strong>Monthly Statistics</strong>
                            </div>
                            <div class="card-body">
                                <div class="chart-area" style="height: 300px;">
                                    <!-- Placeholder for chart -->
                                    <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                                        <span class="text-muted">Monthly Statistics Chart</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card health-metric-card mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-users text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Total Population</p>
                                        <span class="h3">1,245</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card health-metric-card mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success">
                                            <i class="fe fe-file-text text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Documents Issued</p>
                                        <span class="h3">156</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card health-metric-card mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning">
                                            <i class="fe fe-activity text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Active Programs</p>
                                        <span class="h3">12</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Here we would add chart initialization code
    // For example, using Chart.js or another charting library
    console.log('Analytics page loaded');
</script>
@endpush
@endsection