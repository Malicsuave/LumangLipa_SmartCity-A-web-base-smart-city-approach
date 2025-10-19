/**
 * Chart.js Plugin: 3D Pie/Doughnut Effect
 * Adds a 3D-like visual effect to pie and doughnut charts
 */

const ChartPlugin3DPie = {
  id: 'chart3DPie',
  
  beforeInit: function(chart) {
    // Only apply to pie and doughnut charts
    if (chart.config.type !== 'pie' && chart.config.type !== 'doughnut') {
      return;
    }
    
    // Store original draw function
    chart.originalDraw = chart.draw;
    
    // Override the draw function
    chart.draw = function() {
      // Call original draw
      chart.originalDraw.apply(this, arguments);
      
      // Get chart context
      const ctx = chart.ctx;
      const width = chart.width;
      const height = chart.height;
      
      // Get chart data
      const meta = chart.getDatasetMeta(0);
      const elements = meta.data;
      const centerX = meta.xCenter || width / 2;
      const centerY = meta.yCenter || height / 2;
      
      // Skip if no elements
      if (!elements.length) return;
      
      // 3D effect parameters
      const depth = chart.options.plugins.chart3DPie?.depth || 15;
      const angle = chart.options.plugins.chart3DPie?.angle || 45;
      const shadeIntensity = chart.options.plugins.chart3DPie?.shadeIntensity || 0.5;
      const angleRadians = (angle * Math.PI) / 180;
      
      // Calculate offsets for 3D effect
      const offsetX = Math.cos(angleRadians) * depth;
      const offsetY = Math.sin(angleRadians) * depth;
      
      // Save current state
      ctx.save();
      
      // Draw 3D sides for each segment
      for (let i = elements.length - 1; i >= 0; i--) {
        const element = elements[i];
        
        // Skip if hidden
        if (!element.hidden && element.options) {
          const model = element;
          
          // Get segment angles
          const startAngle = model.startAngle;
          const endAngle = model.endAngle;
          const radius = model.outerRadius;
          const innerRadius = model.innerRadius || 0;
          
          // Get segment color
          const color = element.options.backgroundColor;
          
          // Draw side of segment (the depth/thickness)
          ctx.save();
          
          // Create gradient for side
          const darkerColor = adjustColor(color, -shadeIntensity);
          const sideGradient = ctx.createLinearGradient(
            centerX, centerY, 
            centerX + offsetX, centerY + offsetY
          );
          sideGradient.addColorStop(0, darkerColor);
          sideGradient.addColorStop(1, color);
          
          // Draw outer arc side
          ctx.beginPath();
          ctx.arc(centerX, centerY, radius, startAngle, endAngle);
          ctx.arc(centerX + offsetX, centerY + offsetY, radius, endAngle, startAngle, true);
          ctx.closePath();
          ctx.fillStyle = sideGradient;
          ctx.fill();
          
          // If doughnut, draw inner arc side
          if (innerRadius > 0) {
            ctx.beginPath();
            ctx.arc(centerX, centerY, innerRadius, startAngle, endAngle);
            ctx.arc(centerX + offsetX, centerY + offsetY, innerRadius, endAngle, startAngle, true);
            ctx.closePath();
            ctx.fillStyle = sideGradient;
            ctx.fill();
          }
          
          ctx.restore();
        }
      }
      
      // Redraw the top of each segment with gradients for 3D effect
      for (let i = 0; i < elements.length; i++) {
        const element = elements[i];
        
        // Skip if hidden
        if (!element.hidden && element.options) {
          const model = element;
          
          // Get segment angles and radius
          const startAngle = model.startAngle;
          const endAngle = model.endAngle;
          const radius = model.outerRadius;
          const innerRadius = model.innerRadius || 0;
          
          // Get segment color
          const color = element.options.backgroundColor;
          
          // Draw top of segment with gradient
          ctx.save();
          
          // Create radial gradient
          const lighterColor = adjustColor(color, shadeIntensity/2);
          const gradient = ctx.createRadialGradient(
            centerX + offsetX * 0.5, centerY + offsetY * 0.5, 0,
            centerX + offsetX * 0.5, centerY + offsetY * 0.5, radius
          );
          gradient.addColorStop(0, lighterColor);
          gradient.addColorStop(1, color);
          
          // Draw top arc
          ctx.beginPath();
          ctx.arc(centerX + offsetX, centerY + offsetY, radius, startAngle, endAngle);
          if (innerRadius > 0) {
            ctx.arc(centerX + offsetX, centerY + offsetY, innerRadius, endAngle, startAngle, true);
          } else {
            ctx.lineTo(centerX + offsetX, centerY + offsetY);
          }
          ctx.closePath();
          ctx.fillStyle = gradient;
          ctx.fill();
          
          ctx.restore();
        }
      }
      
      ctx.restore();
    };
  }
};

// Helper function to adjust color brightness
function adjustColor(color, amount) {
  let result = color;
  
  // Handle hex color format
  if (color.startsWith('#')) {
    result = color.substring(1);
    
    // Convert to RGB
    let r = parseInt(result.substring(0, 2), 16);
    let g = parseInt(result.substring(2, 4), 16);
    let b = parseInt(result.substring(4, 6), 16);
    
    // Adjust brightness
    r = Math.max(0, Math.min(255, Math.round(r + r * amount)));
    g = Math.max(0, Math.min(255, Math.round(g + g * amount)));
    b = Math.max(0, Math.min(255, Math.round(b + b * amount)));
    
    // Convert back to hex
    result = '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
  }
  // Handle rgba color format
  else if (color.startsWith('rgba')) {
    const parts = color.match(/rgba\((\d+),\s*(\d+),\s*(\d+),\s*([\d.]+)\)/);
    if (parts) {
      let r = parseInt(parts[1]);
      let g = parseInt(parts[2]);
      let b = parseInt(parts[3]);
      let a = parseFloat(parts[4]);
      
      // Adjust brightness
      r = Math.max(0, Math.min(255, Math.round(r + r * amount)));
      g = Math.max(0, Math.min(255, Math.round(g + g * amount)));
      b = Math.max(0, Math.min(255, Math.round(b + b * amount)));
      
      result = `rgba(${r}, ${g}, ${b}, ${a})`;
    }
  }
  // Handle rgb color format
  else if (color.startsWith('rgb')) {
    const parts = color.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\)/);
    if (parts) {
      let r = parseInt(parts[1]);
      let g = parseInt(parts[2]);
      let b = parseInt(parts[3]);
      
      // Adjust brightness
      r = Math.max(0, Math.min(255, Math.round(r + r * amount)));
      g = Math.max(0, Math.min(255, Math.round(g + g * amount)));
      b = Math.max(0, Math.min(255, Math.round(b + b * amount)));
      
      result = `rgb(${r}, ${g}, ${b})`;
    }
  }
  
  return result;
}

// Register plugin
if (typeof Chart !== 'undefined') {
  Chart.register(ChartPlugin3DPie);
}