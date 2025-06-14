@extends('layouts.admin')

@section('title', 'GAD Reports')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gender and Development Reports</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.gad.index') }}">GAD Records</a></li>
        <li class="breadcrumb-item active">Reports</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Report Filters
        </div>
        <div class="card-body">
            <form id="report-form" method="GET" action="{{ route('admin.gad.reports') }}">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                            <button type="submit" name="export" value="pdf" class="btn btn-success">
                                <i class="fas fa-file-pdf me-1"></i> Export to PDF
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Gender Identity Distribution
                </div>
                <div class="card-body">
                    <canvas id="genderChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Special Status Distribution
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            Program Participation
        </div>
        <div class="card-body">
            <canvas id="programChart" width="100%" height="30"></canvas>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Summary Data
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-primary">
                            <td colspan="3"><strong>Gender Identity</strong></td>
                        </tr>
                        @foreach($stats['by_gender_identity'] as $gender => $count)
                            <tr>
                                <td>{{ $gender ?? 'Unspecified' }}</td>
                                <td>{{ $count }}</td>
                                <td>{{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                        
                        <tr class="table-success">
                            <td colspan="3"><strong>Special Status</strong></td>
                        </tr>
                        <tr>
                            <td>Pregnant Residents</td>
                            <td>{{ $stats['pregnant_women'] }}</td>
                            <td>{{ $stats['total'] > 0 ? round(($stats['pregnant_women'] / $stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Solo Parents</td>
                            <td>{{ $stats['solo_parents'] }}</td>
                            <td>{{ $stats['total'] > 0 ? round(($stats['solo_parents'] / $stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>VAW Cases</td>
                            <td>{{ $stats['vaw_cases'] }}</td>
                            <td>{{ $stats['total'] > 0 ? round(($stats['vaw_cases'] / $stats['total']) * 100, 1) : 0 }}%</td>
                        </tr>
                        
                        <tr class="table-info">
                            <td colspan="3"><strong>Program Participation</strong></td>
                        </tr>
                        @foreach($stats['program_participation'] as $program => $count)
                            <tr>
                                <td>{{ $program }}</td>
                                <td>{{ $count }}</td>
                                <td>{{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                        
                        <tr class="table-dark">
                            <td><strong>Total GAD Records</strong></td>
                            <td colspan="2"><strong>{{ $stats['total'] }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gender Identity Chart
        const genderData = {
            labels: {!! json_encode($stats['by_gender_identity']->keys()) !!},
            datasets: [{
                data: {!! json_encode($stats['by_gender_identity']->values()) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                ]
            }]
        };
        
        new Chart(document.getElementById('genderChart'), {
            type: 'pie',
            data: genderData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Distribution by Gender Identity'
                    }
                }
            }
        });
        
        // Special Status Chart
        const statusData = {
            labels: ['Pregnant', 'Solo Parents', 'VAW Cases'],
            datasets: [{
                label: 'Count',
                data: [
                    {{ $stats['pregnant_women'] }},
                    {{ $stats['solo_parents'] }},
                    {{ $stats['vaw_cases'] }}
                ],
                backgroundColor: [
                    'rgba(246, 194, 62, 0.7)',
                    'rgba(28, 200, 138, 0.7)',
                    'rgba(231, 74, 59, 0.7)'
                ],
                borderColor: [
                    'rgb(246, 194, 62)',
                    'rgb(28, 200, 138)',
                    'rgb(231, 74, 59)'
                ],
                borderWidth: 1
            }]
        };
        
        new Chart(document.getElementById('statusChart'), {
            type: 'bar',
            data: statusData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribution by Special Status'
                    }
                }
            }
        });
        
        // Program Participation Chart
        const programData = {
            labels: {!! json_encode($stats['program_participation']->keys()) !!},
            datasets: [{
                label: 'Participants',
                data: {!! json_encode($stats['program_participation']->values()) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgb(78, 115, 223)',
                borderWidth: 1
            }]
        };
        
        new Chart(document.getElementById('programChart'), {
            type: 'bar',
            data: programData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Program Participation'
                    }
                }
            }
        });
    });
</script>
@endsection