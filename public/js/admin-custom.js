/**
 * Admin custom JavaScript for sidebar functionality
 */
document.addEventListener('DOMContentLoaded', function() {
  console.log('Admin custom JS loaded');
  
  // Check if the main toggle handler is already set up
  if (window.sidebarToggleSetup) {
    console.log('Sidebar toggle already set up, skipping duplicate setup');
    return;
  }
  
  // Mark that we're setting up the toggle to prevent conflicts
  window.sidebarToggleSetup = true;
  
  // Check initial state and apply correct classes
  function updateSidebarState() {
    const body = document.body;
    const isCollapsed = body.classList.contains('collapsed-menu') || 
                       body.classList.contains('collapsed') ||
                       body.classList.contains('narrow');
    
    if (isCollapsed) {
      body.classList.add('sidebar-collapsed');
      console.log('Sidebar is collapsed');
    } else {
      body.classList.remove('sidebar-collapsed');
      console.log('Sidebar is expanded');
    }
  }
  
  // Run on page load
  updateSidebarState();
  
  // Also listen for any class changes on the body element
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
        console.log('Body class changed:', document.body.className);
        updateSidebarState();
      }
    });
  });
  
  observer.observe(document.body, {
    attributes: true,
    attributeFilter: ['class']
  });
  
  // Handle window resize to ensure proper sidebar behavior
  window.addEventListener('resize', function() {
    setTimeout(updateSidebarState, 100);
  });
  
  // Initialize DataTables on all tables with class 'data-table'
  if (typeof $.fn.DataTable !== 'undefined') {
    $('.data-table').each(function() {
      var tableOptions = {
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        language: {
          search: "<i class='fe fe-search'></i>",
          searchPlaceholder: "Search records",
          paginate: {
            previous: "<i class='fe fe-arrow-left'></i> Previous",
            next: "Next <i class='fe fe-arrow-right'></i>"
          }
        },
        dom: '<"row align-items-center"<"col-md-6"l><"col-md-6"f>><"table-responsive"t><"row align-items-center"<"col-md-6"i><"col-md-6"p>>'
      };
      
      // Check if this table already has DataTable initialized
      if (!$.fn.dataTable.isDataTable(this)) {
        $(this).DataTable(tableOptions);
      }
    });
  }
});