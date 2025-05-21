/**
 * Admin custom JavaScript for sidebar functionality
 */
document.addEventListener('DOMContentLoaded', function() {
  // Get the sidebar collapse buttons
  const collapseButtons = document.querySelectorAll('.collapseSidebar');
  
  // Add click event to toggle the sidebar-collapsed class
  collapseButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      document.body.classList.toggle('sidebar-collapsed');
    });
  });
  
  // Check initial state (if the sidebar is already collapsed)
  if (document.body.classList.contains('collapsed-menu')) {
    document.body.classList.add('sidebar-collapsed');
  }
});