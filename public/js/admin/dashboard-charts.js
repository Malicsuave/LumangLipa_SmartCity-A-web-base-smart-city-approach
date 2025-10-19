// Simple vanilla JavaScript implementation for dashboard charts
// This approach avoids conflicts with other libraries

document.addEventListener('DOMContentLoaded', function() {
    // Debug info - check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded. Please check your includes.');
        
        // Add visual error indicator to chart containers
        const chartContainers = document.querySelectorAll('[id$="Chart"]');
        chartContainers.forEach(container => {
            container.innerHTML = '<div style="padding: 20px; color: red; text-align: center;">Chart library not loaded</div>';
        });
        return;
    }

    console.log('Chart.js version:', Chart.version);
    
    // Function to safely initialize a chart
    function initializeChart(canvasId, config) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.error('Chart canvas not found:', canvasId);
            return null;
        }
        
        try {
            const ctx = canvas.getContext('2d');
            return new Chart(ctx, config);
        } catch (error) {
            console.error('Error initializing chart:', canvasId, error);
            canvas.innerHTML = '<div style="padding: 20px; color: red; text-align: center;">Error loading chart</div>';
            return null;
        }
    }
    
    // Get chart data from global variables (will be set in the view)
    if (typeof dashboardChartData === 'undefined') {
        console.error('Dashboard chart data not available');
        return;
    }
    
    // Resident Types Chart
    initializeChart('residentTypesChart', {
        type: 'pie',
        data: {
            labels: dashboardChartData.resident_types.labels,
            datasets: [{
                data: dashboardChartData.resident_types.data,
                backgroundColor: dashboardChartData.resident_types.colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right'
            }
        }
    });
    
    // Gender Distribution Chart
    initializeChart('genderChart', {
        type: 'doughnut',
        data: {
            labels: dashboardChartData.gender_distribution.labels,
            datasets: [{
                data: dashboardChartData.gender_distribution.data,
                backgroundColor: dashboardChartData.gender_distribution.colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right'
            }
        }
    });
    
    // Age Groups Chart
    initializeChart('ageGroupsChart', {
        type: 'bar',
        data: {
            labels: dashboardChartData.age_groups.labels,
            datasets: [{
                label: 'Number of Residents',
                data: dashboardChartData.age_groups.data,
                backgroundColor: dashboardChartData.age_groups.colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    
    // Monthly Registrations Chart
    initializeChart('monthlyRegistrationsChart', {
        type: 'line',
        data: {
            labels: dashboardChartData.monthly_registrations.labels,
            datasets: [{
                label: 'New Registrations',
                data: dashboardChartData.monthly_registrations.data,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: '#3B82F6',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }]
            }
        }
    });
});