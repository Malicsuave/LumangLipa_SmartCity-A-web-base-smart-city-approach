/**
 * Common DataTable Functionality for Admin Pages
 * Provides reusable functions and configurations for DataTables
 */

// Common DataTable configuration
const commonDataTableConfig = {
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    pageLength: 10,
    lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
    scrollX: false,
    scrollCollapse: false,
    pagingType: "simple_numbers",
    processing: false,
    serverSide: false,
    language: {
        search: "",
        searchPlaceholder: "Search...",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
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
        // Custom pagination: limit to 4 consecutive numbers
        limitPaginationTo4Numbers();
        
        // Apply search placeholder and icon
        applySearchStyling();
        
        // Fix dropdown positioning to prevent disappearing and overlap
        fixDropdownPositioning();
        
        // PAGINATION SIZE FIX - Force consistent button dimensions after each redraw
        setTimeout(function() {
            fixPaginationButtonSizes('table');
        }, 50);
    }
};

// Function to force consistent pagination button sizes
function fixPaginationButtonSizes(tableSelector) {
    // Fix all pagination buttons and page-links in all tables
    $('.dataTables_paginate .paginate_button, .dataTables_paginate .page-link').each(function() {
        const $btn = $(this);
        
        // Apply base styles with !important to prevent size changes
        $btn.attr('style', 
            'padding: 0.375rem 0.75rem !important; ' +
            'margin: 0 !important; ' +
            'margin-left: -1px !important; ' +
            'line-height: 1.25 !important; ' +
            'color: #007bff !important; ' +
            'background-color: #fff !important; ' +
            'border: 1px solid #dee2e6 !important; ' +
            'text-decoration: none !important; ' +
            'font-size: 0.875rem !important; ' +
            'border-radius: 0 !important; ' +
            'min-width: 36px !important; ' +
            'text-align: center !important; ' +
            'box-shadow: none !important; ' +
            'transition: none !important; ' +
            'display: inline-block !important; ' +
            'float: none !important; ' +
            'position: relative !important; ' +
            'vertical-align: middle !important;'
        );
        
        // Apply state-specific styles
        if ($btn.hasClass('current') || $btn.parent().hasClass('active')) {
            $btn.css({
                'background-color': '#007bff !important',
                'border-color': '#007bff !important',
                'color': '#fff !important',
                'z-index': '3 !important'
            });
        } else if ($btn.hasClass('disabled') || $btn.parent().hasClass('disabled')) {
            $btn.css({
                'background-color': '#fff !important',
                'border-color': '#dee2e6 !important',
                'color': '#6c757d !important',
                'cursor': 'not-allowed !important',
                'pointer-events': 'none !important'
            });
        } else {
            $btn.css({
                'background-color': '#fff !important',
                'border-color': '#dee2e6 !important',
                'color': '#007bff !important',
                'cursor': 'pointer !important'
            });
        }
        
        // Fix first and last button border radius
        if ($btn.hasClass('first') || $btn.parent().is(':first-child')) {
            $btn.css({
                'margin-left': '0 !important',
                'border-top-left-radius': '0.25rem !important',
                'border-bottom-left-radius': '0.25rem !important'
            });
        }
        if ($btn.hasClass('last') || $btn.parent().is(':last-child')) {
            $btn.css({
                'border-top-right-radius': '0.25rem !important',
                'border-bottom-right-radius': '0.25rem !important'
            });
        }
        
        // Add hover event with size lock
        $btn.off('mouseenter.sizefix mouseleave.sizefix').on('mouseenter.sizefix', function() {
            if (!$(this).hasClass('current') && !$(this).hasClass('disabled') && !$(this).parent().hasClass('active') && !$(this).parent().hasClass('disabled')) {
                $(this).css({
                    'background-color': '#e9ecef !important',
                    'border-color': '#dee2e6 !important',
                    'color': '#0056b3 !important',
                    'z-index': '2 !important'
                });
            }
        }).on('mouseleave.sizefix', function() {
            if (!$(this).hasClass('current') && !$(this).hasClass('disabled') && !$(this).parent().hasClass('active') && !$(this).parent().hasClass('disabled')) {
                $(this).css({
                    'background-color': '#fff !important',
                    'border-color': '#dee2e6 !important',
                    'color': '#007bff !important'
                });
            }
        });
    });
    
    // Fix ellipsis elements
    $('.dataTables_paginate .ellipsis').attr('style',
        'min-width: 36px !important; ' +
        'max-width: 36px !important; ' +
        'width: 36px !important; ' +
        'height: 36px !important; ' +
        'min-height: 36px !important; ' +
        'max-height: 36px !important; ' +
        'line-height: 34px !important; ' +
        'padding: 0 !important; ' +
        'margin: 0 2px !important; ' +
        'box-sizing: border-box !important; ' +
        'display: inline-block !important; ' +
        'text-align: center !important; ' +
        'border: 1px solid transparent !important; ' +
        'border-radius: 0.25rem !important; ' +
        'background-color: transparent !important; ' +
        'color: #6c757d !important; ' +
        'font-size: 0.875rem !important; ' +
        'cursor: default !important;'
    );
}

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
    
    // PAGINATION FIX - Add event listeners for pagination changes
    table.on('draw.dt', function() {
        // Force pagination button consistency after any table redraw
        setTimeout(function() {
            fixPaginationButtonSizes(tableId);
        }, 50);
    });
    
    // Listen for pagination clicks specifically
    $(tableId + '_wrapper').on('click', '.dataTables_paginate .paginate_button', function() {
        setTimeout(function() {
            fixPaginationButtonSizes(tableId);
        }, 100);
    });
    
    // Initial pagination fix
    setTimeout(function() {
        fixPaginationButtonSizes(tableId);
    }, 200);
    
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
            // Always enforce Font Awesome font and icon visibility
            $icon.css({
                'font-family': '"Font Awesome 5 Free", "Font Awesome 5 Pro", "Font Awesome 5 Brands"',
                'font-style': 'normal',
                'font-variant': 'normal',
                'text-rendering': 'auto',
                'line-height': '1',
                '-webkit-font-smoothing': 'antialiased',
                'display': 'inline-block',
                'font-weight': classes.includes('fas') ? '900' : (classes.includes('far') ? '400' : 'normal'),
                'visibility': 'visible',
                'color': '' // Let color inherit from parent
            });
            // Ensure icon is not hidden by accidental display:none or visibility:hidden
            $icon.show().css('visibility', 'visible');
        });
    }, 500);
}

// Common status badge formatter
function getStatusBadge(status) {
    const statusMap = {
        'active': '<span class="badge badge-success">Active</span>',
        'inactive': '<span class="badge badge-secondary">Inactive</span>',
        'pending': '<span class="badge badge-warning">Pending</span>',
        'approved': '<span class="badge badge-success">Approved</span>',
        'rejected': '<span class="badge badge-danger">Rejected</span>',
        'claimed': '<span class="badge badge-info">Claimed</span>'
    };
    return statusMap[status] || '<span class="badge badge-secondary">Unknown</span>';
}

// Common date formatter
function formatDate(dateString) {
    try {
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? (dateString || 'N/A') : date.toLocaleString();
    } catch (e) {
        return dateString || 'N/A';
    }
}

// Loading state management
function showLoadingState(message = 'Loading...') {
    // You can customize this to use your preferred loading indicator
    $('.dataTables_processing').text(message).show();
}

function hideLoadingState() {
    $('.dataTables_processing').hide();
}

// Error handling
function handleAjaxError(xhr, status, error) {
    const message = xhr.responseJSON?.message || error || 'An error occurred';
    
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

// Limit pagination to show only 4 consecutive page numbers (prev 1234 next)
function limitPaginationTo4Numbers() {
    $('.dataTables_paginate').each(function() {
        const $paginate = $(this);
        const $buttons = $paginate.find('.paginate_button');
        
        if ($buttons.length <= 6) return; // Already 4 or fewer numbers (prev + 4 numbers + next)
        
        const $current = $buttons.filter('.current');
        if ($current.length === 0) return;
        
        const currentIndex = $buttons.index($current);
        const $prev = $buttons.filter('.previous');
        const $next = $buttons.filter('.next');
        
        // Get all number buttons (exclude prev/next)
        const $numberButtons = $buttons.not('.previous, .next');
        const currentPage = parseInt($current.text());
        const totalPages = $numberButtons.length;
        
        // Calculate which 4 consecutive numbers to show
        let startPage, endPage;
        
        if (currentPage <= 2) {
            // Show pages 1, 2, 3, 4
            startPage = 1;
            endPage = Math.min(4, totalPages);
        } else if (currentPage >= totalPages - 1) {
            // Show last 4 pages
            startPage = Math.max(1, totalPages - 3);
            endPage = totalPages;
        } else {
            // Show current page in middle of 4 consecutive pages
            startPage = currentPage - 1;
            endPage = currentPage + 2;
        }
        
        // Hide all number buttons first
        $numberButtons.hide();
        
        // Show only the 4 consecutive pages
        $numberButtons.each(function() {
            const pageNum = parseInt($(this).text());
            if (pageNum >= startPage && pageNum <= endPage) {
                $(this).show();
            }
        });
        
        // Always show prev/next buttons
        $prev.show();
        $next.show();
    });
}

// Apply search input styling with placeholder and icon
function applySearchStyling() {
    $('.dataTables_filter input').each(function() {
        if (!$(this).attr('placeholder')) {
            $(this).attr('placeholder', 'Search');
        }
    });
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
    
    // Global pagination fix for all tables
    setInterval(function() {
        if ($('.dataTables_paginate .paginate_button').length > 0) {
            fixPaginationButtonSizes('global');
        }
    }, 1000);
});

// Fix dropdown positioning to prevent disappearing and navbar overlap
function fixDropdownPositioning() {
    $('.dataTables_wrapper table .dropdown-toggle').off('click.dropdown-positioning').on('click.dropdown-positioning', function(e) {
        const $toggle = $(this);
        const $dropdown = $toggle.closest('.dropdown');
        const $menu = $dropdown.find('.dropdown-menu');
        
        // Wait for dropdown to open
        setTimeout(function() {
            if ($dropdown.hasClass('show')) {
                const toggleOffset = $toggle.offset();
                const windowHeight = $(window).height();
                const windowWidth = $(window).width();
                const scrollTop = $(window).scrollTop();
                const navbarHeight = $('.main-header').outerHeight() || 60;
                const menuHeight = $menu.outerHeight();
                const menuWidth = $menu.outerWidth();
                
                let top = toggleOffset.top + $toggle.outerHeight() + 2;
                let left = toggleOffset.left;
                
                // Prevent overlap with navbar
                const minTop = scrollTop + navbarHeight + 10;
                if (top < minTop) {
                    top = minTop;
                }
                
                // Prevent going below viewport
                const maxTop = scrollTop + windowHeight - menuHeight - 10;
                if (top > maxTop) {
                    top = Math.max(maxTop, minTop);
                    // If still too tall, reduce height and add scroll
                    if (menuHeight > windowHeight - navbarHeight - 20) {
                        $menu.css({
                            'max-height': (windowHeight - navbarHeight - 20) + 'px',
                            'overflow-y': 'auto'
                        });
                    }
                }
                
                // Prevent going off-screen horizontally
                if (left + menuWidth > windowWidth - 10) {
                    left = windowWidth - menuWidth - 10;
                }
                if (left < 10) {
                    left = 10;
                }
                
                // Apply positioning
                $menu.css({
                    'position': 'fixed',
                    'top': top + 'px',
                    'left': left + 'px',
                    'z-index': '10000',
                    'transform': 'none',
                    'will-change': 'auto'
                });
            }
        }, 10);
    });
    
    // Don't close dropdowns when scrolling - let them stay open
    // Removed scroll close behavior to keep dropdowns visible
}

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
    showConfirmDialog,
    fixPaginationButtonSizes,
    limitPaginationTo4Numbers,
    applySearchStyling,
    fixDropdownPositioning
};
