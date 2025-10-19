@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row mb-2 align-items-center">
                <div class="col">
                    <h2 class="h5 page-title">Analytics Dashboard</h2>
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="row my-4">
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-users fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Residents
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalResidents) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-user-plus fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            New This Month
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($newResidentsThisMonth) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-clock fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Registrations
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingPreRegistrations) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-file-text fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Document Requests
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalDocumentRequests) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Gender Distribution</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Age Groups</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Trends -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Monthly Registration Trends</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="trendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Residents</h6>
                        </div>
                        <div class="card-body">
                            @foreach($recentResidents as $resident)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $resident->first_name }} {{ $resident->last_name }}</div>
                                    <small class="text-muted">{{ $resident->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Documents</h6>
                        </div>
                        <div class="card-body">
                            @foreach($recentDocuments as $document)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $document->document_type }}</div>
                                    <small class="text-muted">{{ $document->created_at->format('M d, Y') }}</small>
                                </div>
                                <span class="badge badge-{{ $document->status == 'approved' ? 'success' : ($document->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($document->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Complaints</h6>
                        </div>
                        <div class="card-body">
                            @foreach($recentComplaints as $complaint)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $complaint->formatted_complaint_type }}</div>
                                    <small class="text-muted">{{ $complaint->created_at->format('M d, Y') }}</small>
                                </div>
                                <span class="badge badge-{{ $complaint->status == 'resolved' ? 'success' : ($complaint->status == 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gender Distribution Chart
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($genderDistribution)) !!},
        datasets: [{
            data: {!! json_encode(array_values($genderDistribution)) !!},
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Age Groups Chart
const ageCtx = document.getElementById('ageChart').getContext('2d');
new Chart(ageCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($ageGroups)) !!},
        datasets: [{
            data: {!! json_encode(array_values($ageGroups)) !!},
            backgroundColor: ['#f6c23e', '#e74a3b', '#858796']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Monthly Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($monthlyRegistrations)) !!},
        datasets: [{
            label: 'New Registrations',
            data: {!! json_encode(array_values($monthlyRegistrations)) !!},
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection