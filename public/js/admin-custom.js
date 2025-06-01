/**
 * Admin custom JavaScript for sidebar functionality
 */
document.addEventListener('DOMContentLoaded', function() {
  // Check initial state and apply correct classes
  function updateSidebarState() {
    const body = document.body;
    const isCollapsed = body.classList.contains('collapsed-menu') || 
                       body.classList.contains('vertical') && body.classList.contains('collapsed') ||
                       body.classList.contains('vertical') && body.classList.contains('narrow');
    
    if (isCollapsed) {
      body.classList.add('sidebar-collapsed');
    } else {
      body.classList.remove('sidebar-collapsed');
    }
  }
  
  // Run on page load
  updateSidebarState();
  
  // Add click event to toggle the sidebar-collapsed class
  const collapseButtons = document.querySelectorAll('.collapseSidebar');
  collapseButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      // Wait for the core sidebar collapse to complete
      setTimeout(updateSidebarState, 50);
      // Also check again after a longer delay to catch any framework animations
      setTimeout(updateSidebarState, 300);
    });
  });
  
  // Also listen for any class changes on the body element
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
        updateSidebarState();
      }
    });
  });
  
  observer.observe(document.body, {
    attributes: true,
    attributeFilter: ['class']
  });
});