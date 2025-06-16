/* Admin JS bundle */

// Import only files that don't have problematic dependencies
import '../../public/admin/dark/js/jquery.stickOnScroll.js';
import '../../public/admin/dark/js/tinycolor-min.js';
import '../../public/admin/dark/js/config.js';
import '../../public/admin/dark/js/apps.js';

// Import custom scripts
import '../../public/js/admin-custom.js';
import '../../public/js/theme-enhancer.js';

// Load all problematic libraries dynamically to avoid Vite build issues
const loadExternalLibraries = () => {
    const libraries = [
        '/admin/dark/js/jquery.min.js',
        '/admin/dark/js/popper.min.js',
        '/admin/dark/js/moment.min.js',
        '/admin/dark/js/bootstrap.min.js',
        '/admin/dark/js/simplebar.min.js',
        '/admin/dark/js/daterangepicker.js'
    ];

    libraries.forEach((lib, index) => {
        const script = document.createElement('script');
        script.src = lib;
        script.async = false; // Load in order
        if (index === 0) {
            script.onload = () => console.log('External libraries loading...');
        }
        if (index === libraries.length - 1) {
            script.onload = () => console.log('All external libraries loaded');
        }
        document.head.appendChild(script);
    });
};

// Initialize theme preference
document.addEventListener('DOMContentLoaded', () => {
    loadExternalLibraries();
    console.log('Admin bundle loaded successfully');
});