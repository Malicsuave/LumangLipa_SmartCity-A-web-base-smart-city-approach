# DataTable Components Usage Guide

This guide shows how to use the reusable DataTable components in different pages.

## Components Created

1. **`components/datatable.blade.php`** - Base DataTable component
2. **`components/admin-datatable.blade.php`** - Extended admin-specific DataTable
3. **`components/documents-table.blade.php`** - Specialized documents table

## Usage Examples

### 1. Basic DataTable

```blade
<x-datatable 
    id="myTable"
    title="My Data Table"
    :columns="[
        'ID',
        'Name', 
        'Email',
        'Created At'
    ]"
    :actions="true"
    :export-buttons="true">
    
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->created_at->format('M d, Y') }}</td>
        <td>
            <button class="btn btn-sm btn-primary edit-user" data-id="{{ $user->id }}">Edit</button>
            <button class="btn btn-sm btn-danger delete-user" data-id="{{ $user->id }}">Delete</button>
        </td>
    </tr>
    @endforeach
</x-datatable>
```

### 2. Admin DataTable with Modal Handlers

```blade
<x-admin-datatable 
    id="usersTable"
    title="Users Management"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Name'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'role', 'title' => 'Role']
    ]"
    :modal-handlers="[
        ['class' => 'edit-user', 'type' => 'edit', 'function' => 'showEditModal'],
        ['class' => 'delete-user', 'type' => 'delete', 'function' => 'showDeleteModal'],
        ['class' => 'view-user', 'type' => 'view', 'function' => 'viewUserDetails']
    ]">
    
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>
        <td>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    Actions
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item view-user" href="#" data-id="{{ $user->id }}">View</a>
                    <a class="dropdown-item edit-user" href="#" data-id="{{ $user->id }}">Edit</a>
                    <a class="dropdown-item delete-user" href="#" data-id="{{ $user->id }}">Delete</a>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</x-admin-datatable>

@push('scripts')
<script>
function showEditModal(userId) {
    // Your edit modal logic
    $('#editModal').modal('show');
}

function showDeleteModal(userId) {
    // Your delete modal logic
    $('#deleteModal').modal('show');
}

function viewUserDetails(userId) {
    // Your view details logic
    $.get(`/admin/users/${userId}`)
        .done(function(data) {
            // Populate and show modal
        });
}
</script>
@endpush
```

### 3. AJAX-Powered DataTable

```blade
<x-admin-datatable 
    id="ajaxTable"
    title="AJAX Data Table"
    :columns="[
        ['data' => 'id', 'name' => 'id'],
        ['data' => 'name', 'name' => 'name'],
        ['data' => 'email', 'name' => 'email'],
        ['data' => 'actions', 'name' => 'actions', 'orderable' => false, 'searchable' => false]
    ]"
    ajax-url="/admin/users/data"
    :server-side="true"
    :modal-handlers="[
        ['class' => 'edit-user', 'type' => 'edit', 'function' => 'editUser']
    ]">
</x-admin-datatable>
```

### 4. Documents Table (Ready to Use)

```blade
<x-documents-table :document-requests="$documentRequests" />

<!-- Include the required modals -->
@include('admin.modals.document-modals')
```

## Required Modals for Documents Table

Make sure your page includes these modals:
- `#viewDetailsModal`
- `#approveModal` 
- `#rejectModal`
- `#markClaimedModal`
- `#documentPreviewModal`
- `#enlargeReceiptModal`

## Advanced Configuration

### Custom Column Definitions

```blade
<x-datatable 
    :column-defs="[
        ['targets' => 0, 'visible' => false],
        ['targets' => 2, 'orderable' => false],
        ['targets' => -1, 'className' => 'text-center']
    ]">
```

### Custom Buttons

```blade
<x-datatable 
    :custom-buttons="[
        ['text' => 'Custom Export', 'action' => 'function() { alert(\'Custom action\'); }']
    ]">
```

## File Locations

- Base component: `resources/views/components/datatable.blade.php`
- Admin component: `resources/views/components/admin-datatable.blade.php`
- Documents component: `resources/views/components/documents-table.blade.php`

## Dependencies

The components automatically include:
- DataTables CSS/JS
- Buttons extension (for exports)
- Bootstrap 4 integration
- Required export libraries (JSZip, PDFMake)

## Notes

1. Make sure your layout includes jQuery before the components
2. The master layout should have `@stack('styles')` and `@stack('scripts')`
3. All components are responsive by default
4. CSRF token is automatically handled for AJAX requests
