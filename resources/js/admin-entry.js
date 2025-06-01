/* Admin JS bundle */

// Import jQuery first since other scripts depend on it
import '../../public/admin/dark/js/jquery.min.js';

// Import other admin JavaScript files
import '../../public/admin/dark/js/popper.min.js';
import '../../public/admin/dark/js/moment.min.js';
import '../../public/admin/dark/js/bootstrap.min.js';
import '../../public/admin/dark/js/simplebar.min.js';
import '../../public/admin/dark/js/daterangepicker.js';
import '../../public/admin/dark/js/jquery.stickOnScroll.js';
import '../../public/admin/dark/js/tinycolor-min.js';
import '../../public/admin/dark/js/config.js';
import '../../public/admin/dark/js/apps.js';

// Import custom scripts
import '../../public/js/admin-custom.js';
import '../../public/js/theme-enhancer.js';

// Initialize theme preference
document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin bundle loaded successfully');
});