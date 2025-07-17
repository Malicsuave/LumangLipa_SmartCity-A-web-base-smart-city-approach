@extends('layouts.admin.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/modern-dashboard.css') }}">
@endpush

@section('content')
<div class="row">
    <!-- Welcome Widget -->
    <div class="col-12 mb-4">
        <div class="welcome-widget-outlined p-4 d-flex align-items-center">
            <div>
                <h2 class="card-title mb-1">Welcome to the Lumanglipa Barangay Management System!</h2>
                <p class="card-text mb-0">Manage residents, requests, and barangay services efficiently. Have a great day, <strong>{{ Auth::user()->name }}</strong>!</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                            <i class="fe fe-users text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Total Residents</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $metrics['total_residents'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-success">
                            <i class="fe fe-home text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Households</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $metrics['households_count'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning">
                            <i class="fe fe-user-check text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Family Members</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $metrics['family_members_count'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="document-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info">
                            <i class="fe fe-users text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small mb-0">Total Population</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0">{{ $metrics['total_population'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Resident Types</strong>
            </div>
            <div class="card-body chart-container animate-chart-1" style="height: 350px; position: relative;">
                <canvas id="residentTypesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Gender Distribution</strong>
            </div>
            <div class="card-body chart-container animate-chart-2" style="height: 350px; position: relative;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Age Groups</strong>
            </div>
            <div class="card-body chart-container animate-chart-3" style="height: 350px; position: relative;">
                <canvas id="ageGroupsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Monthly Registrations</strong>
            </div>
            <div class="card-body chart-container animate-chart-4" style="height: 350px; position: relative;">
                <canvas id="monthlyRegistrationsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Role-Based Quick Access -->
  
</div>

<!-- Direct Chart.js script insertion -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
// Counter animation for stats is no longer needed since we're using CSS animations
document.addEventListener('DOMContentLoaded', function() {
    console.log('Using CSS-based metric card animations');
    // The counter animation is now handled by CSS in the metric-card-animations.css file
});

// Directly run chart creation when the script is loaded
(function() {
    // Debug information
    console.log('Direct Chart.js initialization');
    
    try {
        // Resident Types Chart
        var rtCtx = document.getElementById('residentTypesChart').getContext('2d');
        var rtChart = new Chart(rtCtx, {
            type: 'pie',
            data: {
                labels: ['Non-Migrant', 'Migrant', 'Transient'],
                datasets: [{
                    data: [{{ $metrics['non_migrant_count'] }}, {{ $metrics['migrant_count'] }}, {{ $metrics['transient_count'] }}],
                    backgroundColor: ['#3B82F6', '#F59E0B', '#EF4444'],
                    borderWidth: 2,
                    borderColor: 'white'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        fontFamily: "'Poppins', sans-serif",
                        fontColor: '#6c757d'
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeOutCubic',
                    onComplete: function() {
                        // Add shadow to pie segments when animation is complete for 3D effect
                        const chartInstance = this.chart;
                        const ctx = chartInstance.ctx;
                        ctx.shadowColor = 'rgba(0, 0, 0, 0.2)';
                        ctx.shadowBlur = 10;
                        ctx.shadowOffsetX = 5;
                        ctx.shadowOffsetY = 5;
                        ctx.stroke();
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const total = dataset.data.reduce((previous, current) => previous + current);
                            const currentValue = dataset.data[tooltipItem.index];
                            const percentage = Math.round((currentValue/total) * 100);
                            return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                        }
                    },
                    titleFontFamily: "'Poppins', sans-serif",
                    bodyFontFamily: "'Poppins', sans-serif",
                    backgroundColor: 'rgba(45, 55, 72, 0.9)',
                    titleFontColor: 'white',
                    bodyFontColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                }
            }
        });

        // Gender Distribution Chart
        var gdCtx = document.getElementById('genderChart').getContext('2d');
        var gdChart = new Chart(gdCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [{{ $metrics['male_residents'] }}, {{ $metrics['female_residents'] }}],
                    backgroundColor: ['#3B82F6', '#EC4899'],
                    borderWidth: 2,
                    borderColor: 'white'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'right',
                    labels: {
                        fontFamily: "'Poppins', sans-serif",
                        boxWidth: 12,
                        padding: 15,
                        fontColor: '#6c757d'
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2200,
                    easing: 'easeOutQuart'
                },
                cutoutPercentage: 75,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const total = dataset.data.reduce((previous, current) => previous + current);
                            const currentValue = dataset.data[tooltipItem.index];
                            const percentage = Math.round((currentValue/total) * 100);
                            return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                        }
                    },
                    backgroundColor: 'rgba(45, 55, 72, 0.9)',
                    titleFontFamily: "'Poppins', sans-serif",
                    bodyFontFamily: "'Poppins', sans-serif",
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                }
            }
        });

        // Age Groups Chart
        var agCtx = document.getElementById('ageGroupsChart').getContext('2d');
        var agChart = new Chart(agCtx, {
            type: 'bar',
            data: {
                labels: ['Children (0-17)', 'Adults (18-59)', 'Senior Citizens (60+)'],
                datasets: [{
                    label: 'Residents by Age Group',
                    data: [{{ $metrics['children'] }}, {{ $metrics['adults'] }}, {{ $metrics['senior_citizens'] }}],
                    backgroundColor: ['#10B981', '#6366F1', '#F59E0B'],
                    borderWidth: 0,
                    barPercentage: 0.7,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            beginAtZero: true,
                            fontFamily: "'Poppins', sans-serif",
                            fontColor: '#6c757d',
                            padding: 10
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontFamily: "'Poppins', sans-serif",
                            fontColor: '#6c757d',
                            padding: 10
                        }
                    }]
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart',
                    onProgress: function(animation) {
                        const chartInstance = this.chart;
                        const ctx = chartInstance.ctx;
                        const dataset = chartInstance.data.datasets[0];
                        const meta = chartInstance.controller.getDatasetMeta(0);
                        
                        ctx.fillStyle = '#6c757d';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.font = '12px Poppins';
                        
                        meta.data.forEach(function(bar, index) {
                            const data = dataset.data[index];
                            const yPos = bar._model.y - 5;
                            ctx.fillText(data, bar._model.x, yPos);
                        });
                    }
                },
                tooltips: {
                    backgroundColor: 'rgba(45, 55, 72, 0.9)',
                    titleFontFamily: "'Poppins', sans-serif",
                    bodyFontFamily: "'Poppins', sans-serif",
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                }
            }
        });

        // Monthly Registrations Chart
        var mrCtx = document.getElementById('monthlyRegistrationsChart').getContext('2d');
        var gradient = mrCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
        
        var mrChart = new Chart(mrCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['monthly_registrations']['labels']) !!},
                datasets: [{
                    label: 'New Registrations',
                    data: {!! json_encode($chartData['monthly_registrations']['data']) !!},
                    backgroundColor: gradient,
                    borderColor: '#3B82F6',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: '#3B82F6',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                    labels: {
                        fontFamily: "'Poppins', sans-serif",
                        fontColor: '#6c757d',
                        boxWidth: 12,
                        padding: 15
                    }
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)',
                            zeroLineColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            beginAtZero: true,
                            precision: 0,
                            fontFamily: "'Poppins', sans-serif",
                            fontColor: '#6c757d',
                            padding: 10
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontFamily: "'Poppins', sans-serif",
                            fontColor: '#6c757d',
                            padding: 10
                        }
                    }]
                },
                animation: {
                    duration: 2500,
                    easing: 'easeOutQuart'
                },
                tooltips: {
                    backgroundColor: 'rgba(45, 55, 72, 0.9)',
                    titleFontFamily: "'Poppins', sans-serif",
                    bodyFontFamily: "'Poppins', sans-serif",
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                }
            }
        });
        
        console.log('All charts initialized with modern styling');
    } catch (error) {
        console.error('Chart initialization error:', error);
        alert('Error initializing charts: ' + error.message);
    }
})();
</script>
@endsection

@section('scripts')
<!-- Any additional scripts would go here -->
@endsection
