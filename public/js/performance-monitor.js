// Performance monitoring script
(function() {
    // Performance metrics
    const metrics = {
        pageLoad: performance.now(),
        resources: [],
        errors: []
    };

    // Monitor resource loading
    const observer = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
            metrics.resources.push({
                name: entry.name,
                duration: entry.duration,
                type: entry.initiatorType
            });
        }
    });

    // Start observing resource timing
    observer.observe({ entryTypes: ['resource'] });

    // Monitor errors
    window.addEventListener('error', (event) => {
        metrics.errors.push({
            message: event.message,
            source: event.filename,
            line: event.lineno,
            column: event.colno,
            time: performance.now()
        });
    });

    // Log performance metrics on page unload
    window.addEventListener('beforeunload', () => {
        const totalLoadTime = performance.now() - metrics.pageLoad;
        console.log('Page Performance Metrics:', {
            totalLoadTime: `${totalLoadTime.toFixed(2)}ms`,
            resourceCount: metrics.resources.length,
            errorCount: metrics.errors.length
        });
    });
})(); 