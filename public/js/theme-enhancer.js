/**
 * Theme Enhancer for Lumanglipa Admin
 * Handles responsive design and enhanced sidebar functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Fix for sidebar collapse and logo switching
  function handleSidebarState() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar-left');
    
    if (body.classList.contains('collapsed-menu')) {
      body.classList.add('sidebar-collapsed');
    } else {
      body.classList.remove('sidebar-collapsed');
    }
  }
  
  // Initialize on page load
  handleSidebarState();
  
  // Listen for sidebar toggle clicks
  document.querySelectorAll('.collapseSidebar').forEach(button => {
    button.addEventListener('click', function() {
      // Wait a bit for the core toggle to complete
      setTimeout(handleSidebarState, 100);
    });
  });

  // Apply tablet and mobile optimizations
  enhanceMobileExperience();
});

/**
 * Enhance mobile experience with better touch support and responsive behavior
 */
function enhanceMobileExperience() {
  // Make tables responsive on mobile devices
  const tables = document.querySelectorAll('.table:not(.no-responsive)');
  tables.forEach(table => {
    table.classList.add('responsive-table-card');
    
    // Add data attributes for mobile view
    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
    
    const bodyRows = table.querySelectorAll('tbody tr');
    bodyRows.forEach(row => {
      const cells = row.querySelectorAll('td');
      cells.forEach((cell, index) => {
        if (headers[index]) {
          cell.setAttribute('data-title', headers[index]);
        }
      });
    });
  });

  // Add back-to-top button for mobile users
  const backToTop = document.createElement('button');
  backToTop.className = 'back-to-top btn btn-primary btn-sm';
  backToTop.innerHTML = '<i class="fe fe-arrow-up"></i>';
  backToTop.style.position = 'fixed';
  backToTop.style.bottom = '20px';
  backToTop.style.right = '20px';
  backToTop.style.display = 'none';
  backToTop.style.zIndex = '1000';
  backToTop.style.opacity = '0.7';
  backToTop.style.borderRadius = '50%';
  backToTop.style.width = '40px';
  backToTop.style.height = '40px';
  document.body.appendChild(backToTop);
  
  // Show/hide back-to-top button based on scroll position
  window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
      backToTop.style.display = 'block';
    } else {
      backToTop.style.display = 'none';
    }
  });
  
  // Smooth scroll to top when button is clicked
  backToTop.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}