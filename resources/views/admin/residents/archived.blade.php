@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Archived</li>
@endsection

@section('page-title', 'Archived Residents')
@section('page-subtitle', 'View and manage archived resident records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Archived Residents</h1>
                <p class="text-muted mb-0">View and restore archived resident records</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.archived-residents') }}" class="btn btn-info mr-2">
                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                </a>
                <a href="{{ route('admin.residents.index') }}" class="btn btn-primary">
                    <i class="fas fa-users mr-2"></i>
                    Active Residents
                </a>
                <a href="{{ route('admin.residents.create') }}" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-user-plus mr-2"></i>
                    Register New Resident
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Archived Residents</strong>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($archivedResidents->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="archivedTable" data-export-title="Archived Residents">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Age/Gender</th>
                                <th>Contact</th>
                                <th>Archived Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedResidents as $resident)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
                                <tr>
                                    <td><strong>{{ $resident->barangay_id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                @if($resident->photo)
                                                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" 
                                                         class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ substr($resident->first_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                                @if($resident->middle_name)
                                                    {{ substr($resident->middle_name, 0, 1) }}.
                                                @endif
                                                {{ $resident->suffix }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $resident->type_of_resident }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                        <br><small class="text-muted">{{ $resident->sex }}</small>
                                    </td>
                                    <td>{{ $resident->contact_number ?: 'N/A' }}</td>
                                    <td>
                                        {{ $resident->deleted_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $resident->deleted_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center table-actions-col">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmRestore('{{ $resident->id }}', '{{ $resident->first_name }} {{ $resident->last_name }}')">
                                                    <i class="fas fa-undo mr-2 text-success"></i>Restore
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); confirmPermanentDelete('{{ $resident->id }}', '{{ $resident->first_name }} {{ $resident->last_name }}')">
                                                    <i class="fas fa-trash-alt mr-2"></i>Delete Permanently
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Restore Form -->
                                        <form id="restore-form-{{ $resident->id }}" action="{{ route('admin.residents.restore', $resident->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <!-- Permanent Delete Form -->
                                        <form id="permanent-delete-form-{{ $resident->id }}" action="{{ route('admin.residents.force-delete', $resident->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Barangay ID</th>
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
                            <h5 class="text-muted">No archived residents found</h5>
                            <p class="text-muted">There are no archived residents matching your search criteria.</p>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'purok']))
                        <a href="{{ route('admin.residents.archived') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times mr-2"></i>Clear filters
                        </a>
                    @endif
                </div>
                @endif
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
        if (window.DataTableHelpers) {
            DataTableHelpers.initDataTable('#archivedTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 5, 'desc' ]] // Sort by archived date descending
            });
        }
    });

    // Confirmation functions
    function confirmRestore(residentId, residentName) {
        Swal.fire({
            title: 'Restore Resident?',
            text: `Are you sure you want to restore ${residentName}? This will move them back to active residents.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-undo mr-2"></i>Yes, Restore',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`restore-form-${residentId}`).submit();
            }
        });
    }

    function confirmPermanentDelete(residentId, residentName) {
        Swal.fire({
            title: 'Permanently Delete?',
            text: `Are you sure you want to permanently delete ${residentName}? This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Yes, Delete Forever',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`permanent-delete-form-${residentId}`).submit();
            }
        });
    }

    // Auto-scroll to results if filters are applied
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('search') || urlParams.get('type') || urlParams.get('gender') || urlParams.get('age_group') || urlParams.get('purok')) {
            setTimeout(function() {
                const table = document.getElementById('archivedTable');
                if (table) {
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 400);
        }
    });
</script>
@endpush