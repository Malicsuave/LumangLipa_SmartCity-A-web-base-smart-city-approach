@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin.master')

@section('title', 'Analytics Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
.small-box .metric-counter {
    font-size: 2.2rem;
    font-weight: bold;
}
.analytics-chart-container {
    height: 300px;
    position: relative;
}
.real-time-indicator {
    position: fixed;
    top: 80px;
    right: 20px;
    background: rgba(255, 255, 255, 0.95);
    padding: 10px 15px;
    border-radius: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.2);
    font-size: 13px;
    z-index: 1000;
    border: 2px solid #28a745;
    min-width: 120px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.real-time-indicator:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}
.real-time-indicator i {
    margin-right: 5px;
}
.analytics-card {
    transition: transform 0.2s ease;
}
.analytics-card:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-line mr-2 text-primary"></i>Analytics Dashboard
                </h1>
                <p class="text-muted mb-0">Real-time insights for LUMANGLIPA SMART CITY</p>
            </div>
            <div class="col-auto">
                <div class="real-time-indicator">
                    <i class="fas fa-circle text-success animate__animated animate__pulse animate__infinite"></i>
                    <span>Live Data</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="row mb-4">
    <!-- Total Residents -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info analytics-card">
            <div class="inner">
                <h3 class="metric-counter">{{ number_format($totalResidents) }}</h3>
                <p>Total Residents</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <!-- New This Month -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success analytics-card">
            <div class="inner">
                <h3 class="metric-counter">{{ number_format($newResidentsThisMonth) }}</h3>
                <p>New This Month</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>
    
    <!-- Pending Registrations -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning analytics-card">
            <div class="inner">
                <h3 class="metric-counter">{{ number_format($pendingPreRegistrations) }}</h3>
                <p>Pending Registrations</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <!-- Document Requests -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary analytics-card">
            <div class="inner">
                <h3 class="metric-counter">{{ number_format($totalDocumentRequests) }}</h3>
                <p>Document Requests</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-text"></i>
            </div>
        </div>
    </div>
</div>

<!-- Citizen Satisfaction & System Usage Row -->
<div class="row mb-4">
    <!-- Citizen Satisfaction -->
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star text-warning mr-2"></i>Citizen Satisfaction
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-star"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Average Rating</span>
                                <span class="info-box-number feedback-average">{{ $feedbackMetrics['average_rating'] }}/5</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-thumbs-up"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Satisfaction</span>
                                <span class="info-box-number feedback-satisfaction">{{ $feedbackMetrics['satisfaction_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <small class="text-muted feedback-total">
                    <i class="fas fa-info-circle mr-1"></i>
                    Total Feedbacks: {{ number_format($feedbackMetrics['total_feedbacks']) }}
                </small>
            </div>
        </div>
    </div>
    
    <!-- System Usage -->
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-desktop text-info mr-2"></i>System Usage
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-mouse-pointer"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Activities Today</span>
                                <span class="info-box-number activities-today">{{ number_format($userActivityMetrics['activities_today']) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Suspicious</span>
                                <span class="info-box-number suspicious-count">{{ number_format($userActivityMetrics['suspicious_activities']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <small class="text-muted weekly-activities">
                    <i class="fas fa-calendar-week mr-1"></i>
                    Weekly Activities: {{ number_format($userActivityMetrics['activities_this_week']) }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Announcements & Chatbot Row -->
<div class="row mb-4">
    <!-- Announcements Analytics -->
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bullhorn text-success mr-2"></i>Announcements
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-bullhorn"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active</span>
                                <span class="info-box-number announcements-published">{{ number_format($announcementMetrics['active_announcements']) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Registrations</span>
                                <span class="info-box-number announcements-month">{{ number_format($announcementMetrics['registrations_this_month']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <small class="text-muted announcements-total">
                    <i class="fas fa-calendar mr-1"></i>
                    Total Announcements: {{ number_format($announcementMetrics['total_announcements']) }}
                </small>
            </div>
        </div>
    </div>
    
    <!-- Chatbot Analytics -->
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-robot text-primary mr-2"></i>AI Chatbot
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-primary">
                                <i class="fas fa-comments"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Conversations</span>
                                <span class="info-box-number chatbot-conversations">{{ number_format($chatbotMetrics['total_conversations']) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Today</span>
                                <span class="info-box-number chatbot-today">{{ number_format($chatbotMetrics['conversations_today']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <small class="text-muted chatbot-responses">
                    <i class="fas fa-reply mr-1"></i>
                    Total Responses: {{ number_format($chatbotMetrics['total_responses']) }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Gender Distribution -->
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie text-info mr-2"></i>Gender Distribution
                </h3>
            </div>
            <div class="card-body">
                <div class="analytics-chart-container">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Age Distribution -->
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar text-success mr-2"></i>Age Distribution
                </h3>
            </div>
            <div class="card-body">
                <div class="analytics-chart-container">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-lg border-0 admin-card-shadow analytics-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line text-primary mr-2"></i>User Activity Trends (Last 30 Days)
                </h3>
            </div>
            <div class="card-body">
                <div style="height: 400px;">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js Configuration
Chart.defaults.font.family = 'Nunito Sans, sans-serif';
Chart.defaults.font.size = 12;

// Initialize Charts
function initializeCharts() {
    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($genderDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($genderDistribution)) !!},
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Age Distribution Chart
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($ageDistribution)) !!},
            datasets: [{
                label: 'Residents',
                data: {!! json_encode(array_values($ageDistribution)) !!},
                backgroundColor: '#28a745',
                borderColor: '#1e7e34',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Activity Trends Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($activityTrends)) !!},
            datasets: [{
                label: 'User Activities',
                data: {!! json_encode(array_values($activityTrends)) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });
}

// Real-time Updates
function updateAnalytics() {
    const indicator = document.querySelector('.real-time-indicator');
    if (indicator) {
        indicator.innerHTML = '<i class="fas fa-sync-alt fa-spin text-primary"></i><span>Updating...</span>';
    }

    console.log('Starting analytics update...');

    fetch('/admin/analytics/realtime-data')
        .then(response => {
            console.log('Response received:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Real-time data received:', data);
            
            // Update main statistics
            if (data.totalResidents !== undefined) {
                console.log('Updating main statistics...');
                updateMetricValue('.metric-counter', data.totalResidents, 0);
                updateMetricValue('.metric-counter', data.newResidentsThisMonth, 1);
                updateMetricValue('.metric-counter', data.pendingPreRegistrations, 2);
                updateMetricValue('.metric-counter', data.totalDocumentRequests, 3);
            }

            // Update feedback metrics
            if (data.feedbackMetrics) {
                console.log('Updating feedback metrics...');
                updateTextValue('.feedback-average', data.feedbackMetrics.average_rating + '/5');
                updateTextValue('.feedback-satisfaction', data.feedbackMetrics.satisfaction_rate + '%');
                updateTextValue('.feedback-total', 'Total Feedbacks: ' + numberFormat(data.feedbackMetrics.total_feedbacks));
            }

            // Update activity metrics
            if (data.userActivityMetrics) {
                console.log('Updating activity metrics...');
                updateTextValue('.activities-today', numberFormat(data.userActivityMetrics.activities_today));
                updateTextValue('.suspicious-count', numberFormat(data.userActivityMetrics.suspicious_activities));
                updateTextValue('.weekly-activities', 'Weekly Activities: ' + numberFormat(data.userActivityMetrics.activities_this_week));
            }

            // Update announcement metrics
            if (data.announcementMetrics) {
                console.log('Updating announcement metrics...');
                updateTextValue('.announcements-published', numberFormat(data.announcementMetrics.active_announcements));
                updateTextValue('.announcements-month', numberFormat(data.announcementMetrics.registrations_this_month));
                updateTextValue('.announcements-total', 'Total Announcements: ' + numberFormat(data.announcementMetrics.total_announcements));
            }

            // Update chatbot metrics
            if (data.chatbotMetrics) {
                console.log('Updating chatbot metrics...');
                updateTextValue('.chatbot-conversations', numberFormat(data.chatbotMetrics.total_conversations));
                updateTextValue('.chatbot-today', numberFormat(data.chatbotMetrics.conversations_today));
                updateTextValue('.chatbot-responses', 'Total Responses: ' + numberFormat(data.chatbotMetrics.total_responses));
            }

            // Reset indicator
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-circle text-success animate__animated animate__pulse animate__infinite"></i><span>Live Data</span>';
            }

            console.log('Analytics updated at:', new Date().toLocaleTimeString());
        })
        .catch(error => {
            console.error('Error updating analytics:', error);
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-exclamation-circle text-danger"></i><span>Update Failed</span>';
            }
        });
}

// Helper Functions
function updateMetricValue(selector, value, index) {
    const elements = document.querySelectorAll(selector);
    if (elements[index]) {
        animateCounter(elements[index], value);
    }
}

function updateTextValue(selector, value) {
    const element = document.querySelector(selector);
    if (element) {
        element.textContent = value;
        element.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            element.classList.remove('animate__animated', 'animate__pulse');
        }, 1000);
    }
}

function animateCounter(element, targetValue) {
    const currentValue = parseInt(element.textContent.replace(/,/g, '')) || 0;
    const increment = (targetValue - currentValue) / 20;
    let current = currentValue;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
            current = targetValue;
            clearInterval(timer);
        }
        element.textContent = numberFormat(Math.round(current));
    }, 50);
}

function numberFormat(num) {
    return new Intl.NumberFormat().format(num);
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Analytics dashboard initialized');
    initializeCharts();
    
    // Start real-time updates every 10 seconds for testing (change to 30 later)
    setInterval(updateAnalytics, 10000);
    
    // Initial update after 2 seconds
    setTimeout(updateAnalytics, 2000);
    
    // Manual update button (for testing)
    const indicator = document.querySelector('.real-time-indicator');
    if (indicator) {
        indicator.style.cursor = 'pointer';
        indicator.addEventListener('click', function() {
            console.log('Manual update triggered');
            updateAnalytics();
        });
    }
});
</script>
@endpush