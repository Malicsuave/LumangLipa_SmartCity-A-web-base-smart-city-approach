/**
 * Theme Enhancer for Lumanglipa Admin
 * Handles responsive design and enhanced sidebar functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  console.log('Theme enhancer loaded');
  
  // Fix for sidebar collapse and logo switching
  function handleSidebarState() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar-left');
    
    if (body.classList.contains('collapsed-menu') || body.classList.contains('collapsed')) {
      body.classList.add('sidebar-collapsed');
    } else {
      body.classList.remove('sidebar-collapsed');
    }
  }
  
  // Initialize on page load
  handleSidebarState();
  
  // Listen for sidebar toggle clicks with more specific targeting
  document.addEventListener('click', function(e) {
    // Check if clicked element or its parent has the collapseSidebar class
    if (e.target.closest('.collapseSidebar') || e.target.closest('#sidebarToggle')) {
      console.log('Sidebar toggle detected by theme enhancer');
      // Wait a bit for the core toggle to complete
      setTimeout(handleSidebarState, 100);
      setTimeout(handleSidebarState, 300);
    }
  });

  // Apply tablet and mobile optimizations
  enhanceMobileExperience();
});

/**
 * Enhance mobile experience with better touch support and responsive behavior
 */
function enhanceMobileExperience() {
  // Add touch-friendly hover effects for mobile devices
  if ('ontouchstart' in window) {
    document.body.classList.add('touch-device');
    
    // Handle touch events for sidebar items
    const sidebarItems = document.querySelectorAll('.sidebar-left .nav-item');
    sidebarItems.forEach(item => {
      item.addEventListener('touchstart', function() {
        this.classList.add('touch-active');
      });
      
      item.addEventListener('touchend', function() {
        setTimeout(() => {
          this.classList.remove('touch-active');
        }, 150);
      });
    });
  }
  
  // Improve responsive behavior
  function handleResponsiveChanges() {
    const body = document.body;
    const isSmallScreen = window.innerWidth < 992;
    
    if (isSmallScreen) {
      // On small screens, always collapse sidebar initially
      if (!body.classList.contains('collapsed')) {
        body.classList.add('narrow');
      }
    }
  }
  
  // Run on load and resize
  handleResponsiveChanges();
  window.addEventListener('resize', handleResponsiveChanges);
}