/**
 * Dropdown Menu Fix for Tables with Few Rows
 * This script ensures dropdown menus are properly displayed in tables with minimal content
 */
document.addEventListener('DOMContentLoaded', function() {
    // Function to position dropdown menus correctly
    function fixDropdownPosition(dropdown) {
        const button = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (!button || !menu) return;
        
        // Get position data
        const buttonRect = button.getBoundingClientRect();
        const menuRect = menu.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        const windowWidth = window.innerWidth;
        
        // Check if we're in a table with few rows
        const tableRows = dropdown.closest('tbody')?.querySelectorAll('tr');
        const hasFewRows = tableRows && tableRows.length <= 3;
        
        // Calculate available space
        const spaceBelow = windowHeight - buttonRect.bottom;
        const spaceAbove = buttonRect.top;
        
        // Determine if menu should appear above or below
        if (hasFewRows && menuRect.height > spaceBelow && spaceAbove > menuRect.height) {
            // Position above
            menu.style.top = 'auto';
            menu.style.bottom = '100%';
            menu.style.left = '0';
            menu.style.transform = 'none';
            menu.style.maxHeight = 'none';
            menu.style.overflowY = 'visible';
        } else {
            // Position below but ensure it's fully visible
            menu.style.top = '100%';
            menu.style.bottom = 'auto';
            menu.style.left = '0';
            menu.style.right = 'auto';
            menu.style.transform = 'none';
            menu.style.maxHeight = 'none';
            menu.style.overflowY = 'visible';
            
            // If menu would go off-screen to the right, align it right
            if (buttonRect.left + menuRect.width > windowWidth) {
                menu.style.left = 'auto';
                menu.style.right = '0';
            }
        }
        
        // Add active class to improve z-index
        dropdown.classList.add('dropdown-active');
    }
    
    // Find all dropdowns in the document
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // Apply fix to each dropdown
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.dropdown-toggle');
        if (!button) return;
        
        // When showing dropdown
        button.addEventListener('click', function(e) {
            // Give menu a chance to render
            setTimeout(() => {
                fixDropdownPosition(dropdown);
            }, 10);
        });
    });
    
    // Additional event handling for Bootstrap
    if (typeof jQuery !== 'undefined') {
        jQuery('.dropdown').on('show.bs.dropdown', function() {
            const dropdown = this;
            setTimeout(() => {
                fixDropdownPosition(dropdown);
            }, 10);
        });
        
        jQuery('.dropdown').on('hidden.bs.dropdown', function() {
            this.classList.remove('dropdown-active');
        });
    }
});

/**
 * Custom dropdown positioning fix for tables with single rows
 * This script fixes the issue where dropdowns become scrollable when there's only one row in a table
 */
(function($) {
    // Fix for dropdown menus in tables with only one row
    $(document).ready(function() {
        // Ensure dropdown menus in tables with single rows display correctly
        $(document).on('show.bs.dropdown', function(e) {
            // Check if this dropdown is inside a table with only one row
            const $table = $(e.target).closest('.table');
            if ($table.length && $table.find('tbody tr').length === 1) {
                const $dropdownMenu = $(e.target).find('.dropdown-menu');
                
                // Position fix for dropdowns in tables with single row
                $dropdownMenu.css({
                    'position': 'absolute',
                    'transform': 'none !important', 
                    'top': '100%',
                    'left': 'auto',
                    'right': '0',
                    'will-change': 'top, left'
                });
                
                // Ensure table doesn't scroll horizontally due to dropdown
                $table.closest('.table-responsive').css({
                    'overflow-x': 'visible',
                    'overflow-y': 'visible'
                });
            }
        });
        
        // Reset table-responsive styling when dropdown is hidden
        $(document).on('hidden.bs.dropdown', function() {
            // Only reset if not in a context where another dropdown is showing
            if (!$('.dropdown-menu.show').length) {
                $('.table-responsive').css({
                    'overflow-x': '',
                    'overflow-y': ''
                });
            }
        });
    });
})(jQuery);
