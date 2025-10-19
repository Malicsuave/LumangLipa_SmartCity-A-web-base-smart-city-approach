@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
<li class="breadcrumb-item active" aria-current="page">Archived</li>
@endsection

@section('page-title', 'Archived Senior Citizens')
@section('page-subtitle', 'View and manage archived senior citizen records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Archived Senior Citizens</h1>
                <p class="text-muted mb-0">View and restore archived senior citizen records</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.senior-citizens') }}" class="btn btn-info mr-2">
                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                </a>
                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-primary">
                    <i class="fas fa-users mr-2"></i>
                    Active Senior Citizens
                </a>
                <a href="{{ route('admin.senior-citizens.register') }}" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-user-plus mr-2"></i>
                    Register Senior Citizen
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Archived Senior Citizens</strong>
            </div>
            <div class="card-body">
                @if($archivedSeniorCitizens->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="archivedTable" data-export-title="Archived Senior Citizens">
                        <thead>
                            <tr>
                                <th>Senior ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Age/Gender</th>
                                <th>Contact</th>
                                <th>Archived Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedSeniorCitizens as $senior)
                                <tr>
                                    <td><strong>{{ $senior->senior_id_number ?: 'Not Assigned' }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                @if($senior->photo)
                                                    <img src="{{ asset('storage/' . $senior->photo) }}" alt="{{ $senior->first_name }} {{ $senior->last_name }}" 
                                                         class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ substr($senior->first_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $senior->last_name }}, {{ $senior->first_name }}</strong>
                                                @if($senior->middle_name)
                                                    {{ substr($senior->middle_name, 0, 1) }}.
                                                @endif
                                                {{ $senior->suffix }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>Senior Citizen</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($senior->birthdate)->age }} years old
                                        <br><small class="text-muted">{{ $senior->sex }}</small>
                                    </td>
                                    <td>{{ $senior->contact_number ?: 'N/A' }}</td>
                                    <td>
                                        {{ $senior->deleted_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $senior->deleted_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmRestore('{{ $senior->id }}', '{{ $senior->first_name }} {{ $senior->last_name }}')">
                                                    <i class="fas fa-undo mr-2"></i>Restore
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmPermanentDelete('{{ $senior->id }}', '{{ $senior->first_name }} {{ $senior->last_name }}')">
                                                    <i class="fas fa-trash-alt mr-2"></i>Delete Permanently
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Restore Form -->
                                        <form id="restore-form-{{ $senior->id }}" action="{{ route('admin.senior-citizens.restore', $senior->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <!-- Permanent Delete Form -->
                                        <form id="permanent-delete-form-{{ $senior->id }}" action="{{ route('admin.senior-citizens.force-delete', $senior->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Senior ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Age/Gender</th>
                                <th>Contact</th>
                                <th>Archived Date</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @else
                <div class="text-center py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="text-center">
                            <i class="fas fa-archive fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No archived senior citizens found</h5>
                            <p class="text-muted">There are no archived senior citizens matching your search criteria.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-primary">
                        <i class="fas fa-users mr-2"></i>View Active Senior Citizens
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreSeniorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restore Senior Citizen</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore <strong id="restoreSeniorName"></strong>?</p>
                <p class="text-muted"><small>This action will move the senior citizen back to the active list.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmRestore">Restore Senior Citizen</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Permanently Confirmation Modal -->
<div class="modal fade" id="deleteSeniorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Senior Citizen Permanently</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete <strong id="deleteSeniorName"></strong>?</p>
                <p class="text-muted"><small>This action cannot be undone and will remove all associated data permanently.</small></p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Warning:</strong> This will permanently delete all data for this senior citizen.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Permanently</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
html {
    scroll-behavior: smooth;
}

.dropdown-menu {
    z-index: 9999 !important;
}
</style>
@endpush

@push('scripts')
@include('admin.components.datatable-scripts')
<script>
    // Initialize DataTable
    document.addEventListener('DOMContentLoaded', function() {
        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#archivedTable')) {
            $('#archivedTable').DataTable().destroy();
        }
        if (window.DataTableHelpers) {
            DataTableHelpers.initDataTable('#archivedTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 5, 'desc' ]] // Sort by archived date descending
            });
        }
    });

function confirmRestore(id, name) {
    document.getElementById('restoreSeniorName').textContent = name;
    document.getElementById('confirmRestore').onclick = function() {
        document.getElementById('restore-form-' + id).submit();
    };
    $('#restoreSeniorModal').modal('show');
}

function confirmPermanentDelete(id, name) {
    document.getElementById('deleteSeniorName').textContent = name;
    document.getElementById('confirmDelete').onclick = function() {
        document.getElementById('permanent-delete-form-' + id).submit();
    };
    $('#deleteSeniorModal').modal('show');
}
</script>
@endpush
