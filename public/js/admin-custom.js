// Admin custom JavaScript for sidebar functionality
// Minimal admin custom JS for sidebar and DataTables

// Check if the main toggle handler is already set up
if (!window.sidebarToggleSetup) {
  window.sidebarToggleSetup = true;

  // Check initial state and apply correct classes
  function updateSidebarState() {
    const body = document.body;
    const isCollapsed = body.classList.contains('collapsed-menu') || 
                       body.classList.contains('collapsed') ||
                       body.classList.contains('narrow');
    if (isCollapsed) {
      body.classList.add('sidebar-collapsed');
    } else {
      body.classList.remove('sidebar-collapsed');
    }
  }

  // Run on page load
  updateSidebarState();

  // Remove MutationObserver for performance; updateSidebarState only on load and resize

  // Handle window resize to ensure proper sidebar behavior
  window.addEventListener('resize', function() {
    updateSidebarState();
  });

  // Initialize DataTables only once per table, and never re-initialize
  if (typeof $.fn.DataTable !== 'undefined') {
    $('.data-table').each(function() {
      if (!$.fn.dataTable.isDataTable(this)) {
        $(this).DataTable({
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
        });
      }
    });
  }

  // Sidebar toggle logic (minimal, safe)
  $(function() {
    $(".collapseSidebar").on("click", function(e) {
      e.preventDefault();
      if ($(window).width() < 992) {
        $(".vertical").toggleClass("narrow open");
      } else {
        $(".vertical").toggleClass("collapsed");
        $(".vertical").removeClass("hover");
      }
    });

    $(document).on("click", function(e) {
      if (
        $(window).width() < 992 &&
        !$(e.target).closest(".sidebar-left, .collapseSidebar").length &&
        $(".vertical").hasClass("open")
      ) {
        $(".vertical").removeClass("open");
      }
    });

    $(window).on("resize", function() {
      if ($(window).width() >= 992) {
        $(".vertical").removeClass("open narrow");
      } else {
        $(".vertical").addClass("narrow").removeClass("collapsed hover");
      }
    });

    $(".sidebar-left").hover(
      function() {
        if ($(window).width() >= 992 && $(".vertical").hasClass("collapsed")) {
          $(".vertical").addClass("hover");
        }
      },
      function() {
        if ($(window).width() >= 992 && $(".vertical").hasClass("collapsed")) {
          $(".vertical").removeClass("hover");
        }
      }
    );
  });
}