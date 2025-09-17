# Admin Tables and DataTables - Reusable Components

This document explains how to use the reusable table components and DataTable functionality across different admin pages.

## Files Created

### CSS Files
- `/public/css/admin/tables.css` - Common styles for all admin tables and DataTables
- `/public/css/admin/documents.css` - Document-specific styles (example of page-specific CSS)

### JavaScript Files
- `/public/js/admin/datatable-helpers.js` - Common DataTable functionality and helpers

### Blade Components
- `/resources/views/admin/components/datatable-styles.blade.php` - Reusable CSS includes for DataTables
- `/resources/views/admin/components/datatable-scripts.blade.php` - Reusable JS includes for DataTables

## How to Use in Other Admin Pages

### 1. Basic Setup

In any admin page that needs DataTables, include the components in your Blade template:

```blade
@push('styles')
@include('admin.components.datatable-styles')
<!-- Add page-specific styles if needed -->
<link rel="stylesheet" href="{{ asset('css/admin/your-page.css') }}">
@endpush

@push('scripts')
@include('admin.components.datatable-scripts')
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>

<script>
$(function () {
    // Initialize DataTable with common configuration
    const myTable = DataTableHelpers.initDataTable("#myTable", {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
});
</script>
@endpush
```

### 2. Available Helper Functions

#### DataTable Initialization
```javascript
// Basic initialization
const table = DataTableHelpers.initDataTable("#tableId");

// With custom configuration
const table = DataTableHelpers.initDataTable("#tableId", {
    buttons: ["copy", "csv", "excel"],
    pageLength: 50,
    order: [[ 1, "asc" ]]
});
```

#### Status Badges
```javascript
// Use default status badges
const badge = DataTableHelpers.getStatusBadge('pending');

// Use custom status badges
const badge = DataTableHelpers.getStatusBadge('custom_status', {
    'custom_status': '<span class="badge badge-warning">Custom</span>'
});
```

#### Date Formatting
```javascript
// Default formatting (MMM DD, YYYY)
const date = DataTableHelpers.formatDate('2025-01-15');

// Custom formatting
const date = DataTableHelpers.formatDate('2025-01-15', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
});
```

#### Loading States
```javascript
// Show loading
DataTableHelpers.showLoadingState('#myContainer');

// Hide loading
DataTableHelpers.hideLoadingState('#myContainer');
```

#### Error Handling
```javascript
$.ajax({
    // ... ajax config
    error: function(xhr, status, error) {
        DataTableHelpers.handleAjaxError(xhr, status, error, 'Loading data');
    }
});
```

### 3. Common Table HTML Structure

```blade
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Your Table Title</h3>
    </div>
    <div class="card-body">
        <table id="yourTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->field1 }}</td>
                    <td>{{ $item->field2 }}</td>
                    <td>
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" 
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="viewItem({{ $item->id }})">
                                    <i class="fas fa-eye mr-2"></i>View
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editItem({{ $item->id }})">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

### 4. Creating Page-Specific CSS

For each page that has unique styling needs, create a CSS file following this pattern:

```css
/* /public/css/admin/your-page.css */

/* Page-specific styles only */
.your-page-specific-class {
    /* styles */
}

/* Don't repeat common table styles - they're in tables.css */
```

### 5. Filter Section (Reusable Pattern)

```blade
<!-- Active Filters Display -->
@if(request()->hasAny(['search', 'status', 'type']))
    <div class="active-filters mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">Active filters:</small>
                <span class="badge badge-info ml-2">{{ $items->total() }} results found</span>
            </div>
            <small class="text-muted">Click on any filter badge to remove it</small>
        </div>
        <div class="mt-2">
            @if(request('search'))
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" 
                   class="filter-badge badge badge-dark">
                    Search: {{ request('search') }} <i class="fe fe-x"></i>
                </a>
            @endif
        </div>
    </div>
@endif
```

## Benefits of This Structure

1. **Consistency** - All admin tables look and behave the same way
2. **Maintainability** - Update common functionality in one place
3. **Performance** - Shared CSS and JS files are cached
4. **Flexibility** - Easy to customize specific pages while maintaining common functionality
5. **Reusability** - New pages can be set up quickly using existing components

## Migration Guide

To convert existing admin pages to use this system:

1. Replace individual DataTable CSS/JS includes with the component includes
2. Move page-specific styles to separate CSS files
3. Replace DataTable initialization with `DataTableHelpers.initDataTable()`
4. Use helper functions for common tasks like status badges and date formatting
5. Apply common HTML structure for tables and dropdowns

## Example Pages Using This System

- **Documents** (`/admin/documents`) - Fully implemented with all features
- **Residents** (`/admin/residents`) - Can be migrated to use these components
- **Complaints** (`/admin/complaints`) - Can be migrated to use these components
- **Audit Logs** (`/admin/audit-logs`) - Can be migrated to use these components
