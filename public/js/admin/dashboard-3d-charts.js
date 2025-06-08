/**
 * Simple 3D Pie Chart Implementation for Chart.js 2.9.4
 * This approach uses simple techniques that are known to work well with Chart.js 2.9.4
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Make sure Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded. Please check your includes.');
        return;
    }
    
    console.log('Dashboard 3D Charts loading...');
    
    // Get canvas element
    const pieCanvas = document.getElementById('residentTypesChart');
    
    // Exit if canvas doesn't exist
    if (!pieCanvas) {
        console.error('Cannot find pie chart canvas element');
        return;
    }
    
    // Get the 2D context from the canvas
    const ctx = pieCanvas.getContext('2d');
    
    // Make sure we have the chart data in the global scope
    if (typeof pie_chart_data === 'undefined') {
        console.error('Pie chart data not found. Make sure pie_chart_data is defined.');
        return;
    }
    
    // Apply shadow to the canvas for 3D effect
    ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
    ctx.shadowBlur = 15;
    ctx.shadowOffsetX = 8;
    ctx.shadowOffsetY = 8;
    
    // Create the pie chart with special options for 3D appearance
    const pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pie_chart_data.labels,
            datasets: [{
                data: pie_chart_data.data,
                backgroundColor: pie_chart_data.colors,
                // Add depth effect with border
                borderColor: '#fff',
                borderWidth: 3,
                // Add shadow within each segment
                hoverBorderColor: '#fff',
                hoverBorderWidth: 5,
                // Use a decent hover offset for 3D effect
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            // Important for 3D appearance
            cutoutPercentage: 0,
            // Legend config
            legend: {
                position: 'right',
                align: 'center',
                labels: {
                    boxWidth: 15,
                    fontFamily: "'Poppins', sans-serif",
                    padding: 15
                }
            },
            // Title for the chart
            title: {
                display: false,
                text: 'Resident Types',
                fontFamily: "'Poppins', sans-serif",
                fontSize: 16
            },
            // Special animation for 3D effect
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 2000,
                easing: 'easeOutBounce'
            },
            // Make the tooltip more visible for 3D appearance
            tooltips: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFontFamily: "'Poppins', sans-serif",
                bodyFontFamily: "'Poppins', sans-serif",
                cornerRadius: 5,
                xPadding: 10,
                yPadding: 10,
                // Custom tooltip to show percentage
                callbacks: {
                    label: function(tooltipItem, data) {
                        const dataset = data.datasets[tooltipItem.datasetIndex];
                        const total = dataset.data.reduce((previous, current) => previous + current);
                        const currentValue = dataset.data[tooltipItem.index];
                        const percentage = Math.round((currentValue/total) * 100);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            },
            // Custom plugin for additional effects
            plugins: {
                beforeDraw: function(chart) {
                    // Add a white shadow behind the chart for depth
                    const ctx = chart.chart.ctx;
                    ctx.save();
                    const chartArea = chart.chartArea;
                    ctx.fillStyle = 'white';
                    ctx.shadowColor = 'rgba(0,0,0,0.2)';
                    ctx.shadowBlur = 20;
                    ctx.shadowOffsetX = 10;
                    ctx.shadowOffsetY = 10;
                    ctx.beginPath();
                    ctx.arc(
                        chartArea.left + (chartArea.right - chartArea.left) / 2,
                        chartArea.top + (chartArea.bottom - chartArea.top) / 2,
                        Math.min((chartArea.right - chartArea.left), (chartArea.bottom - chartArea.top)) / 2,
                        0,
                        2 * Math.PI
                    );
                    ctx.fill();
                    ctx.restore();
                }
            }
        }
    });
    
    console.log('Dashboard 3D Pie Chart initialized');
});