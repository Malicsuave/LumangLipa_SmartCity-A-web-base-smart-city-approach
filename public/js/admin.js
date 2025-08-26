document.addEventListener('DOMContentLoaded', function() {
    function closeAllDropdowns() {
        document.querySelectorAll('.table-dropdown.open').forEach(dd => {
            dd.classList.remove('open');
            const menu = dd.querySelector('.table-dropdown-menu');
            if (menu && menu._portal) {
                document.body.removeChild(menu);
                menu._portal = false;
                dd.appendChild(menu);
                menu.style.top = '';
                menu.style.bottom = '';
                menu.style.left = '';
                menu.style.right = '';
            }
        });
    }

    document.body.addEventListener('click', function(e) {
        if (!e.target.closest('.table-dropdown')) {
            closeAllDropdowns();
        }
    });

    document.querySelectorAll('.table-dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAllDropdowns();
            const dropdown = btn.closest('.table-dropdown');
            dropdown.classList.toggle('open');
            const menu = dropdown.querySelector('.table-dropdown-menu');
            if (dropdown.classList.contains('open')) {
                // Move menu to body (portal)
                const rect = btn.getBoundingClientRect();
                menu._portal = true;
                document.body.appendChild(menu);
                menu.style.position = 'absolute';
                menu.style.minWidth = rect.width + 'px';
                // Calculate space below and above
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;
                const menuHeight = menu.offsetHeight || 120;
                if (spaceBelow < menuHeight && spaceAbove > menuHeight) {
                    // Open upward
                    menu.style.top = (window.scrollY + rect.top - menuHeight) + 'px';
                } else {
                    // Open downward
                    menu.style.top = (window.scrollY + rect.bottom) + 'px';
                }
                menu.style.left = (window.scrollX + rect.right - menu.offsetWidth) + 'px';
            } else {
                // Close and move back
                if (menu._portal) {
                    document.body.removeChild(menu);
                    menu._portal = false;
                    dropdown.appendChild(menu);
                    menu.style.top = '';
                    menu.style.left = '';
                }
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeAllDropdowns();
    });
}); 