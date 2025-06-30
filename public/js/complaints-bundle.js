/**
 * Complaints Module - Optimized JS Bundle
 * This file contains all JS functionality needed for the complaints dashboard
 * to reduce multiple HTTP requests and improve performance
 */

// Initialize the complaints dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize performance metrics display
    initPerformanceDisplay();
    
    // Initialize interactive elements
    initComplaintsUI();
    
    // Handle table data and refresh
    initComplaintsTable();
    
    // Initialize animations and counters
    initAnimations();
});

/**
 * Initialize performance metrics display
 */
function initPerformanceDisplay() {
    const metricElement = document.getElementById('pageLoadMetric');
    if (!metricElement) return;
    
    if (window.performanceMetrics && window.performanceMetrics.totalLoadTime) {
        const loadTime = parseInt(window.performanceMetrics.totalLoadTime);
        metricElement.textContent = 'Page Load: ' + (loadTime / 1000).toFixed(2) + 's';
        
        // Add color coding based on load time
        if (loadTime < 1000) {
            metricElement.classList.add('text-success');
        } else if (loadTime < 3000) {
            metricElement.classList.add('text-warning');
        } else {
            metricElement.classList.add('text-danger');
        }
    }
}

/**
 * Initialize UI components for complaints dashboard
 */
function initComplaintsUI() {
    // Initialize refresh button
    const refreshBtn = document.getElementById('refreshTableBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            refreshComplaintsTable();
        });
    }
    
    // Add hover effects to cards
    document.querySelectorAll('.complaint-metric-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
}

/**
 * Initialize and handle complaints table functionality
 */
function initComplaintsTable() {
    const table = document.querySelector('.complaints-table');
    if (!table) return;
    
    // Add click handlers to table rows for viewing details
    table.querySelectorAll('tbody tr').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function(e) {
            // Ignore clicks on buttons or links
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || 
                e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            
            // Get complaint ID from the first cell
            const idCell = this.querySelector('td:first-child .badge');
            if (idCell) {
                const id = idCell.textContent.replace('#', '');
                window.location.href = `/admin/complaint-management/${id}`;
            }
        });
    });
}

/**
 * Refresh complaints table via AJAX (simulation)
 */
function refreshComplaintsTable() {
    const refreshBtn = document.getElementById('refreshTableBtn');
    if (!refreshBtn) return;
    
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<i class="fe fe-loader fe-spin me-1"></i> Loading...';
    
    // In production, this would be an actual fetch request
    setTimeout(() => {
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = '<i class="fe fe-refresh-cw me-1"></i> Refresh';
        
        // Show notification
        showToast('Complaints table refreshed successfully');
    }, 600);
}

/**
 * Initialize animations including counter animations
 */
function initAnimations() {
    // Animate metric counters with efficient requestAnimationFrame
    document.querySelectorAll('.metric-counter').forEach(counter => {
        const target = parseInt(counter.textContent, 10);
        if (isNaN(target)) return;
        
        const duration = 1000; // ms
        const start = performance.now();
        
        // Reset counter to 0
        counter.textContent = '0';
        
        // Use requestAnimationFrame for smooth animation
        const updateCounter = (timestamp) => {
            const elapsed = timestamp - start;
            const progress = Math.min(elapsed / duration, 1);
            
            counter.textContent = Math.floor(progress * target);
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        // Start animation after a slight delay
        setTimeout(() => requestAnimationFrame(updateCounter), 200);
    });
}

/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} type - The type of message (success, error, warning)
 */
function showToast(message, type = 'success') {
    // Create toast element if it doesn't exist
    let toast = document.querySelector('.toast-notification');
    if (toast) {
        // Remove existing toast if it exists
        document.body.removeChild(toast);
    }
    
    // Create new toast
    toast = document.createElement('div');
    toast.className = 'toast-notification';
    
    // Set icon based on type
    let icon = 'check-circle';
    let iconClass = 'text-success';
    
    if (type === 'error') {
        icon = 'alert-circle';
        iconClass = 'text-danger';
    } else if (type === 'warning') {
        icon = 'alert-triangle';
        iconClass = 'text-warning';
    }
    
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fe fe-${icon} ${iconClass} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Show with animation
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });
    
    // Hide after delay
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Export functions for potential use in other modules
window.ComplaintsModule = {
    refreshTable: refreshComplaintsTable,
    showToast: showToast
};