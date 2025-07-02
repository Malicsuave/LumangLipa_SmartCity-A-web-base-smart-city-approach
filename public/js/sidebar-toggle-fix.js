/**
 * DEFINITIVE SIDEBAR TOGGLE FIX - ULTRA AGGRESSIVE VERSION
 * This script overrides all other sidebar toggle implementations with maximum force
 */

// Immediately prevent any other sidebar toggle handlers from interfering
(function() {
  'use strict';
  
  // Flag to prevent multiple initializations
  if (window.definitiveSidebarToggleLoaded) {
    return;
  }
  window.definitiveSidebarToggleLoaded = true;
  
  console.log('🚀 Loading ULTRA AGGRESSIVE sidebar toggle fix...');
  
  // Our definitive toggle function - with direct CSS manipulation
  function definitiveToggleSidebar() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar-left');
    const mainContent = document.querySelector('.main-content') || document.querySelector('main[role="main"]') || document.querySelector('main');
    const topNav = document.querySelector('.topnav') || document.querySelector('nav.topnav');
    
    console.log('🔄 DEFINITIVE TOGGLE - Current classes:', body.className);
    
    if (!sidebar) {
      console.error('❌ Sidebar not found!');
      return;
    }
    
    // Check current state by looking at sidebar width
    const currentWidth = window.getComputedStyle(sidebar).width;
    const widthValue = parseFloat(currentWidth);
    const isCurrentlyCollapsed = widthValue <= 100;
    
    console.log('📏 Current sidebar width:', currentWidth, 'Parsed value:', widthValue, 'Is collapsed:', isCurrentlyCollapsed);
    
    // Clear all possible sidebar state classes first
    body.classList.remove('collapsed', 'narrow', 'sidebar-collapsed', 'collapsed-menu', 'open', 'hover');
    
    if (isCurrentlyCollapsed) {
      // EXPAND SIDEBAR
      console.log('🔼 EXPANDING sidebar');
      body.classList.add('vertical');
      
      // Force sidebar styles directly
      sidebar.style.minWidth = '16rem';
      sidebar.style.width = '16rem';
      sidebar.style.overflow = 'visible';
      
      // Adjust main content margin
      if (mainContent) {
        mainContent.style.marginLeft = '16rem';
        mainContent.style.transition = 'margin-left 0.3s ease';
      }
      if (topNav) {
        topNav.style.marginLeft = '16rem';
        topNav.style.transition = 'margin-left 0.3s ease';
      }
      
      // Show text elements
      const textElements = sidebar.querySelectorAll('.item-text, .collapse-hide, span:not(.fe):not(.material-icons)');
      textElements.forEach(function(el) {
        el.style.opacity = '1';
        el.style.visibility = 'visible';
        el.style.width = 'auto';
        el.style.overflow = 'visible';
      });
      
      // Logo switching
      const logoExpanded = sidebar.querySelector('.logo-expanded');
      const logoCollapsed = sidebar.querySelector('.logo-collapsed');
      if (logoExpanded) logoExpanded.style.display = 'block';
      if (logoCollapsed) logoCollapsed.style.display = 'none';
      
    } else {
      // COLLAPSE SIDEBAR
      console.log('🔽 COLLAPSING sidebar');
      body.classList.add('vertical', 'narrow');
      
      // Force sidebar styles directly
      sidebar.style.minWidth = '5rem';
      sidebar.style.width = '5rem';
      sidebar.style.overflow = 'hidden';
      
      // Adjust main content margin
      if (mainContent) {
        mainContent.style.marginLeft = '5rem';
        mainContent.style.transition = 'margin-left 0.3s ease';
      }
      if (topNav) {
        topNav.style.marginLeft = '5rem';
        topNav.style.transition = 'margin-left 0.3s ease';
      }
      
      // Hide text elements
      const textElements = sidebar.querySelectorAll('.item-text, .collapse-hide, span:not(.fe):not(.material-icons)');
      textElements.forEach(function(el) {
        el.style.opacity = '0';
        el.style.visibility = 'hidden';
        el.style.width = '0';
        el.style.overflow = 'hidden';
      });
      
      // Logo switching
      const logoExpanded = sidebar.querySelector('.logo-expanded');
      const logoCollapsed = sidebar.querySelector('.logo-collapsed');
      if (logoExpanded) logoExpanded.style.display = 'none';
      if (logoCollapsed) logoCollapsed.style.display = 'block';
    }
    
    // Force reflow and dispatch events
    sidebar.offsetHeight;
    setTimeout(function() {
      window.dispatchEvent(new Event('resize'));
    }, 300);
  }
  
  // Ultra aggressive event handler setup
  function setupUltraAggressiveHandlers() {
    // Find all possible toggle elements
    const selectors = [
      '.collapseSidebar',
      '#sidebarToggle',
      '.sidebar-toggle',
      '[data-toggle="sidebar"]',
      '.navbar-toggler',
      '.hamburger-menu'
    ];
    
    let buttonsFound = 0;
    
    selectors.forEach(function(selector) {
      const elements = document.querySelectorAll(selector);
      elements.forEach(function(element, index) {
        buttonsFound++;
        console.log(`🎯 Setting up ULTRA handler for ${selector} #${index}`);
        
        // Remove ALL existing event listeners by cloning element
        const newElement = element.cloneNode(true);
        element.parentNode.replaceChild(newElement, element);
        
        // Multiple event types with capture and bubble phases
        ['click', 'mousedown', 'touchstart', 'touchend'].forEach(function(eventType) {
          // Capture phase
          newElement.addEventListener(eventType, function(e) {
            if (eventType === 'click' || eventType === 'touchend') {
              e.preventDefault();
              e.stopPropagation();
              e.stopImmediatePropagation();
              console.log(`🚀 ULTRA ${eventType.toUpperCase()} triggered on ${selector}`);
              definitiveToggleSidebar();
            }
          }, true);
          
          // Bubble phase
          newElement.addEventListener(eventType, function(e) {
            if (eventType === 'click' || eventType === 'touchend') {
              e.preventDefault();
              e.stopPropagation();
              e.stopImmediatePropagation();
            }
          }, false);
        });
        
        // Direct onclick override
        newElement.onclick = function(e) {
          e.preventDefault();
          e.stopPropagation();
          console.log('🎯 Direct onclick triggered');
          definitiveToggleSidebar();
          return false;
        };
      });
    });
    
    console.log(`✅ Set up ultra aggressive handlers for ${buttonsFound} elements`);
    return buttonsFound;
  }
  
  // Wait for DOM and set up handlers
  function ready(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }
  
  ready(function() {
    console.log('🚀 ULTRA AGGRESSIVE sidebar toggle: DOM ready');
    
    // Initial setup
    setTimeout(function() {
      setupUltraAggressiveHandlers();
    }, 100);
    
    // Re-setup every 500ms to ensure persistence
    setInterval(function() {
      const buttonsFound = setupUltraAggressiveHandlers();
      if (buttonsFound === 0) {
        console.warn('⚠️ No toggle buttons found, searching again...');
      }
    }, 500);
    
    // Keyboard shortcut (this works, so keep it as is)
    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        console.log('⌨️ KEYBOARD SHORTCUT TRIGGERED');
        definitiveToggleSidebar();
      }
    });
    
    // Global click interceptor as last resort
    document.addEventListener('click', function(e) {
      // Check if the clicked element or its parents match our selectors
      let target = e.target;
      let depth = 0;
      
      while (target && depth < 5) {
        if (target.matches && (
          target.matches('.collapseSidebar') ||
          target.matches('#sidebarToggle') ||
          target.matches('.sidebar-toggle') ||
          target.matches('[data-toggle="sidebar"]')
        )) {
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
          console.log('🔥 GLOBAL INTERCEPTOR caught click on', target);
          definitiveToggleSidebar();
          return false;
        }
        target = target.parentElement;
        depth++;
      }
    }, true); // Use capture phase
    
    // Add hover functionality for collapsed sidebar
    setTimeout(function() {
      const sidebar = document.querySelector('.sidebar-left');
      if (sidebar) {
        sidebar.addEventListener('mouseenter', function() {
          if (document.body.classList.contains('narrow')) {
            console.log('📍 Hovering over collapsed sidebar');
            
            // Try multiple selectors for main content
            const mainContent = document.querySelector('.main-content') || 
                              document.querySelector('main[role="main"]') || 
                              document.querySelector('main');
            const topNav = document.querySelector('.topnav') || 
                          document.querySelector('nav.topnav') || 
                          document.querySelector('.navbar');
            
            // Expand sidebar
            sidebar.style.minWidth = '16rem';
            sidebar.style.width = '16rem';
            sidebar.style.zIndex = '1050';
            sidebar.style.boxShadow = '4px 0 15px rgba(0, 0, 0, 0.15)';
            sidebar.style.transition = 'all 0.3s ease';
            
            // Adjust main content to prevent overlap
            if (mainContent) {
              mainContent.style.marginLeft = '16rem';
              mainContent.style.transition = 'margin-left 0.3s ease';
            }
            
            if (topNav) {
              topNav.style.marginLeft = '16rem';
              topNav.style.transition = 'margin-left 0.3s ease';
            }
            
            // Show text elements temporarily
            const textElements = sidebar.querySelectorAll('.item-text, .collapse-hide, span:not(.fe):not(.material-icons)');
            textElements.forEach(function(el) {
              el.style.opacity = '1';
              el.style.visibility = 'visible';
              el.style.width = 'auto';
              el.style.overflow = 'visible';
              el.style.transition = 'opacity 0.2s ease';
            });
            
            // Show expanded logo, hide collapsed logo
            const logoExpanded = sidebar.querySelector('.logo-expanded');
            const logoCollapsed = sidebar.querySelector('.logo-collapsed');
            if (logoExpanded) logoExpanded.style.display = 'block';
            if (logoCollapsed) logoCollapsed.style.display = 'none';
          }
        });
        
        sidebar.addEventListener('mouseleave', function() {
          if (document.body.classList.contains('narrow')) {
            console.log('📍 Leaving collapsed sidebar');
            
            // Try multiple selectors for main content
            const mainContent = document.querySelector('.main-content') || 
                              document.querySelector('main[role="main"]') || 
                              document.querySelector('main');
            const topNav = document.querySelector('.topnav') || 
                          document.querySelector('nav.topnav') || 
                          document.querySelector('.navbar');
            
            // Collapse sidebar back
            sidebar.style.minWidth = '5rem';
            sidebar.style.width = '5rem';
            sidebar.style.zIndex = '';
            sidebar.style.boxShadow = '';
            sidebar.style.transition = 'all 0.3s ease';
            
            // Reset main content margin
            if (mainContent) {
              mainContent.style.marginLeft = '5rem';
              mainContent.style.transition = 'margin-left 0.3s ease';
            }
            
            if (topNav) {
              topNav.style.marginLeft = '5rem';
              topNav.style.transition = 'margin-left 0.3s ease';
            }
            
            // Hide text elements again
            const textElements = sidebar.querySelectorAll('.item-text, .collapse-hide, span:not(.fe):not(.material-icons)');
            textElements.forEach(function(el) {
              el.style.opacity = '0';
              el.style.visibility = 'hidden';
              el.style.width = '0';
              el.style.overflow = 'hidden';
              el.style.transition = 'opacity 0.2s ease';
            });
            
            // Hide expanded logo, show collapsed logo
            const logoExpanded = sidebar.querySelector('.logo-expanded');
            const logoCollapsed = sidebar.querySelector('.logo-collapsed');
            if (logoExpanded) logoExpanded.style.display = 'none';
            if (logoCollapsed) logoCollapsed.style.display = 'block';
          }
        });
      }
    }, 300);
    
    // Debug functions
    window.testSidebarToggle = function() {
      console.log('🧪 MANUAL TEST TRIGGERED');
      definitiveToggleSidebar();
    };
    
    window.debugSidebarButtons = function() {
      const selectors = ['.collapseSidebar', '#sidebarToggle', '.sidebar-toggle'];
      selectors.forEach(function(selector) {
        const elements = document.querySelectorAll(selector);
        console.log(`${selector}: found ${elements.length} elements`, elements);
      });
    };
    
    console.log('✅ ULTRA AGGRESSIVE sidebar toggle setup complete');
    console.log('🔧 Debug: Use testSidebarToggle() or debugSidebarButtons() in console');
  });
})();