/**
 * Common DataTables initialization script
 * This initializes all tables with class 'data-table' across the application
 */
$(document).ready(function() {
    // Check if DataTables exists before initializing
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.data-table').each(function() {
            // Check if DataTable is already initialized on this element
            if (!$.fn.dataTable.isDataTable(this)) {
                $(this).DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "responsive": true,
                    "pageLength": 10,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>tip',
                    "language": {
                        "paginate": {
                            "previous": "<i class='fe fe-arrow-left'></i> Previous",
                            "next": "Next <i class='fe fe-arrow-right'></i>"
                        },
                        "search": "<i class='fe fe-search'></i>",
                        "searchPlaceholder": "Search records"
                    }
                });
            }
        });
    }
});