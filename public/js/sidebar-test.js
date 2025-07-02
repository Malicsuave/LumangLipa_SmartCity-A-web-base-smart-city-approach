/**
 * Quick test script for sidebar functionality
 * Add this to your browser console to test the sidebar
 */

// Test function to manually toggle sidebar
function testSidebarToggle() {
  console.log('=== Testing Sidebar Toggle ===');
  
  // Check current state
  const body = document.body;
  console.log('Current body classes:', body.className);
  
  // Find toggle buttons
  const toggleButtons = document.querySelectorAll('.collapseSidebar, #sidebarToggle');
  console.log('Found toggle buttons:', toggleButtons.length);
  
  // Test clicking the first button
  if (toggleButtons.length > 0) {
    console.log('Clicking first toggle button...');
    toggleButtons[0].click();
    
    setTimeout(() => {
      console.log('After click - body classes:', document.body.className);
    }, 500);
  } else {
    console.log('No toggle buttons found!');
  }
}

// Run the test
testSidebarToggle();