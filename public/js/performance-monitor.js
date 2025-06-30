/**
 * Enhanced Performance Monitoring Script
 * Tracks detailed metrics about page load performance
 */

// Create global performance metrics object
window.performanceMetrics = {
    startTime: performance.now(),
    resources: {
        total: 0,
        js: 0,
        css: 0,
        images: 0,
        fonts: 0,
        other: 0
    },
    timing: {
        domComplete: 0,
        loadEvent: 0,
        firstContentfulPaint: 0
    },
    errors: [],
    warnings: []
};

// Track resource loading
const resourceObserver = new PerformanceObserver((list) => {
    list.getEntries().forEach(entry => {
        window.performanceMetrics.resources.total++;
        
        // Categorize resources
        const url = entry.name || '';
        if (url.endsWith('.js')) {
            window.performanceMetrics.resources.js++;
        } else if (url.endsWith('.css')) {
            window.performanceMetrics.resources.css++;
        } else if (url.match(/\.(png|jpg|jpeg|gif|svg|webp)$/i)) {
            window.performanceMetrics.resources.images++;
        } else if (url.match(/\.(woff|woff2|ttf|otf|eot)$/i)) {
            window.performanceMetrics.resources.fonts++;
        } else {
            window.performanceMetrics.resources.other++;
        }
        
        // Check for slow resources (> 500ms)
        if (entry.duration > 500) {
            window.performanceMetrics.warnings.push({
                type: 'slow-resource',
                url: entry.name,
                duration: entry.duration.toFixed(2) + 'ms'
            });
        }
    });
});

// Track timing metrics
const timingObserver = new PerformanceObserver((list) => {
    const entries = list.getEntries();
    entries.forEach(entry => {
        if (entry.name === 'first-contentful-paint') {
            window.performanceMetrics.timing.firstContentfulPaint = entry.startTime.toFixed(2);
        }
    });
});

// Initialize observers
if (PerformanceObserver.supportedEntryTypes.includes('resource')) {
    resourceObserver.observe({ entryTypes: ['resource'] });
}

if (PerformanceObserver.supportedEntryTypes.includes('paint')) {
    timingObserver.observe({ entryTypes: ['paint'] });
}

// Capture errors
window.addEventListener('error', function(e) {
    window.performanceMetrics.errors.push({
        message: e.message,
        source: e.filename,
        lineno: e.lineno
    });
});

// Calculate final metrics on window load
window.addEventListener('load', () => {
    // Small delay to ensure all metrics are collected
    setTimeout(() => {
        const loadTime = performance.now() - window.performanceMetrics.startTime;
        window.performanceMetrics.totalLoadTime = loadTime.toFixed(2) + 'ms';
        window.performanceMetrics.timing.domComplete = performance.timing.domComplete - performance.timing.navigationStart;
        window.performanceMetrics.timing.loadEvent = performance.timing.loadEventEnd - performance.timing.navigationStart;
        
        // Log metrics to console
        console.group('🚀 Page Performance Metrics');
        console.log('Total Load Time:', window.performanceMetrics.totalLoadTime);
        console.log('DOM Complete:', window.performanceMetrics.timing.domComplete + 'ms');
        console.log('Resources:', window.performanceMetrics.resources);
        
        if (window.performanceMetrics.warnings.length > 0) {
            console.warn('⚠️ Performance Warnings:', window.performanceMetrics.warnings);
        }
        
        if (window.performanceMetrics.errors.length > 0) {
            console.error('❌ Errors:', window.performanceMetrics.errors);
        }
        console.groupEnd();

        // Add recommendations if load time is too high
        if (loadTime > 3000) {
            console.warn('📝 Performance Recommendations:');
            
            if (window.performanceMetrics.resources.images > 5) {
                console.warn('• Consider optimizing or lazy loading images');
            }
            
            if (window.performanceMetrics.resources.js > 8) {
                console.warn('• Consider bundling JavaScript files');
            }
            
            if (window.performanceMetrics.resources.css > 3) {
                console.warn('• Consider bundling CSS files');
            }
            
            if (window.performanceMetrics.timing.firstContentfulPaint > 2000) {
                console.warn('• Improve First Contentful Paint time');
            }
        }
        
        // Cleanup observers
        resourceObserver.disconnect();
        timingObserver.disconnect();
    }, 0);
});

// Simplified metrics for display in the UI
function getFormattedMetrics() {
    return {
        totalLoadTime: window.performanceMetrics.totalLoadTime || 'calculating...',
        resourceCount: window.performanceMetrics.resources.total,
        errorCount: window.performanceMetrics.errors.length
    };
}