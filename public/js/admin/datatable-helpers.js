/**
 * Common DataTable Functionality for Admin Pages
 * Provides reusable functions and configurations for DataTables
 */

// Common DataTable configuration
const commonDataTableConfig = {
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    pageLength: 25,
    scrollX: false,
    scrollCollapse: false,
    language: {
        search: "Search:",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous"
        },
        emptyTable: "No data available in table",
        zeroRecords: "No matching records found"
    },
    drawCallback: function() {
        // Fix dropdown positioning after each redraw
        $('.dropdown-toggle').off('click.dropdown-fix').on('click.dropdown-fix', function(e) {
            var dropdown = $(this).next('.dropdown-menu');
            var button = $(this);
            var buttonOffset = button.offset();
            var windowWidth = $(window).width();
            var dropdownWidth = dropdown.outerWidth();
            
            // Reset any previous positioning
            dropdown.removeClass('dropdown-menu-right dropdown-menu-left');
            dropdown.css({
                'position': '',
                'top': '',
                'left': '',
                'right': ''
            });
            
            // Check if dropdown would go off-screen horizontally
            if (buttonOffset.left + dropdownWidth > windowWidth - 20) {
                dropdown.addClass('dropdown-menu-right');
            }
            
            // For mobile or small screens, always use right alignment
            if (windowWidth < 768) {
                dropdown.addClass('dropdown-menu-right');
            }
        });
    }
};

// Initialize DataTable with common config plus custom options
function initDataTable(tableId, customConfig = {}) {
    const config = { ...commonDataTableConfig, ...customConfig };

    // Ensure export buttons exclude the last column (typically Actions)
    if (Array.isArray(config.buttons)) {
        // Determine a contextual export title/filename from DOM
        const $tbl = $(tableId);
        let exportTitle = $tbl.data('exportTitle');
        if (!exportTitle || exportTitle.toString().trim() === '') {
            const $cardTitle = $tbl.closest('.card').find('.card-header .card-title').first();
            if ($cardTitle.length) {
                exportTitle = $cardTitle.text().trim();
            }
        }
        if (!exportTitle || exportTitle.toString().trim() === '') {
            const $pageHeader = $('.content-header h1').first();
            exportTitle = ($pageHeader.text() || document.title || 'Export').trim();
        }
        const dateTag = new Date().toISOString().slice(0,19).replace(/[:T]/g, '-');
        const safeFile = (exportTitle || 'Export').replace(/[^a-z0-9\-\_\s]/gi, '').replace(/\s+/g, '_');

        config.buttons = config.buttons.map(function(buttonDef) {
            if (typeof buttonDef === 'string') {
                const base = {
                    extend: buttonDef,
                    exportOptions: { columns: ':visible:not(:last-child)' }
                };
                if (['copy','csv','excel','pdf','print'].includes(base.extend)) {
                    base.title = exportTitle;
                    if (base.extend !== 'print' && base.extend !== 'copy') {
                        base.filename = safeFile + '_' + dateTag;
                    }
                    if (base.extend === 'pdf') {
                        base.orientation = 'portrait';
                        base.pageSize = 'A4';
                    }
                }
                return base;
            }
            // Merge or set exportOptions for object-style button definitions
            const merged = { ...buttonDef };
            merged.exportOptions = {
                columns: ':visible:not(:last-child)',
                ...(buttonDef.exportOptions || {})
            };
            if (['copy','csv','excel','pdf','print'].includes(merged.extend)) {
                merged.title = merged.title || exportTitle;
                if (merged.extend !== 'print' && merged.extend !== 'copy') {
                    merged.filename = merged.filename || (safeFile + '_' + dateTag);
                }
                if (merged.extend === 'pdf') {
                    merged.orientation = merged.orientation || 'portrait';
                    merged.pageSize = merged.pageSize || 'A4';
                }
            }
            return merged;
        });
    }
    
    const table = $(tableId).DataTable(config);
    
    // Add export buttons if specified
    if (config.buttons) {
        table.buttons().container().appendTo(tableId + '_wrapper .col-md-6:eq(0)');
    }
    // Auto-apply shared card/header/pagination styling by adding wrapper classes
    const $wrapper = $(tableId + '_wrapper');
    $wrapper.closest('.card').addClass('admin-card-shadow');
    $wrapper.find('.dataTables_paginate .pagination').addClass('pagination-sm');
    
    return table;
}

// Common dropdown event handlers
function setupDropdownHandlers() {
    // Handle dropdown positioning on window resize
    $(window).on('resize', function() {
        $('.dropdown-menu').removeClass('show');
    });
    
    // Allow dropdown items to close the dropdown but prevent form elements from closing it
    $(document).on('click', '.dropdown-menu .dropdown-item', function(e) {
        // Allow dropdown items to close the dropdown (don't prevent propagation)
    });
    
    // Only prevent dropdown closing for form elements inside dropdown
    $(document).on('click', '.dropdown-menu input, .dropdown-menu select, .dropdown-menu textarea', function(e) {
        e.stopPropagation();
    });
}

// Mobile dropdown handling for action menus within DataTables (including responsive child rows)
function initMobileDropdownsFor(tableSelector, ns) {
    // Attach mobile dropdown behavior for a specific table selector (e.g., #residentTable, #documentsTable)
    $(tableSelector).each(function() {
        const $table = $(this);
        // Remove previous handlers to avoid duplicates
        $table.off('click.mobile-table-' + ns + ' touchstart.mobile-table-' + ns, '.dropdown-toggle');
        $(document).off('click.mobile-table-close-' + ns + ' touchstart.mobile-table-close-' + ns);

        $table.on('click.mobile-table-' + ns + ' touchstart.mobile-table-' + ns, '.dropdown-toggle', function(e) {
            if ($(window).width() <= 768) {
                // Let Bootstrap/AdminLTE handle default dropdown behavior
                // Do not override positioning to match Documents behavior
            }
        });

        // Close on outside click for mobile
        $(document).on('click.mobile-table-close-' + ns + ' touchstart.mobile-table-close-' + ns, function(e) {
            if ($(window).width() <= 768) {
                // Use default outside click behavior
            }
        });
    });
}

// Fix Font Awesome icons in dropdowns
function fixFontAwesomeIcons() {
    setTimeout(function() {
        $('.dropdown-item i').each(function() {
            const $icon = $(this);
            const classes = $icon.attr('class') || '';
            
            // Check if Font Awesome is properly loaded
            if ($icon.css('font-family').indexOf('Font Awesome') === -1) {
                $icon.css({
                    'font-family': '"Font Awesome 5 Free"',
                    'font-style': 'normal',
                    'font-variant': 'normal',
                    'text-rendering': 'auto',
                    'line-height': '1',
                    '-webkit-font-smoothing': 'antialiased',
                    'display': 'inline-block'
                });
            }
            
            // Set proper font weight
            if (classes.includes('fas')) {
                $icon.css('font-weight', '900');
            } else if (classes.includes('far')) {
                $icon.css('font-weight', '400');
            }
        });
    }, 100);
}

// Common status badge helper
function getStatusBadge(status, customStatuses = {}) {
    const defaultStatuses = {
        'pending': '<span class="badge badge-warning">Pending</span>',
        'approved': '<span class="badge badge-success">Approved</span>',
        'active': '<span class="badge badge-success">Active</span>',
        'inactive': '<span class="badge badge-secondary">Inactive</span>',
        'completed': '<span class="badge badge-success">Completed</span>',
        'cancelled': '<span class="badge badge-danger">Cancelled</span>',
        'draft': '<span class="badge badge-secondary">Draft</span>',
        'published': '<span class="badge badge-primary">Published</span>'
    };
    
    const allStatuses = { ...defaultStatuses, ...customStatuses };
    return allStatuses[status] || `<span class="badge badge-secondary">${status}</span>`;
}

// Common date formatter
function formatDate(dateString, options = {}) {
    if (!dateString) return 'N/A';
    
    const defaultOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    };
    
    const formatOptions = { ...defaultOptions, ...options };
    const date = new Date(dateString);
    
    return date.toLocaleDateString('en-US', formatOptions);
}

// Loading state helpers
function showLoadingState(containerId) {
    const container = $(containerId);
    container.addClass('loading-overlay loading');
    
    if (!container.find('.loading-spinner').length) {
        container.append(`
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);
    }
}

function hideLoadingState(containerId) {
    const container = $(containerId);
    container.removeClass('loading-overlay loading');
    container.find('.loading-spinner').remove();
}

// Common AJAX error handler
function handleAjaxError(xhr, status, error, context = '') {
    console.error(`AJAX Error ${context}:`, error);
    
    let message = 'An error occurred. Please try again.';
    
    if (xhr.status === 404) {
        message = 'The requested resource was not found.';
    } else if (xhr.status === 403) {
        message = 'You do not have permission to perform this action.';
    } else if (xhr.status === 500) {
        message = 'Internal server error. Please contact support.';
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
    }
    
    // You can customize this to use your preferred notification system
    if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else {
        alert(message);
    }
}

// Common confirmation dialog
function showConfirmDialog(title, message, onConfirm, onCancel = null) {
    // You can customize this to use your preferred modal/dialog system
    if (confirm(`${title}\n\n${message}`)) {
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
    } else {
        if (typeof onCancel === 'function') {
            onCancel();
        }
    }
}

// Initialize common functionality when DOM is ready
$(document).ready(function() {
    setupDropdownHandlers();
    fixFontAwesomeIcons();
    // Use native Bootstrap/AdminLTE dropdown behavior for both pages
    $(window).on('resize.mobile-table', function() {
        $('.table.dataTable .dropdown-menu').removeClass('show').hide();
        $('.table.dataTable .btn-group').removeClass('show');
    });
    $(window).on('scroll.mobile-table', function() {
        if ($(window).width() <= 768) {
            $('.table.dataTable .dropdown-menu').removeClass('show').hide();
            $('.table.dataTable .btn-group').removeClass('show');
        }
    });
});

// Export functions for use in other scripts
window.DataTableHelpers = {
    commonDataTableConfig,
    initDataTable,
    setupDropdownHandlers,
    fixFontAwesomeIcons,
    getStatusBadge,
    formatDate,
    showLoadingState,
    hideLoadingState,
    handleAjaxError,
    showConfirmDialog
};
