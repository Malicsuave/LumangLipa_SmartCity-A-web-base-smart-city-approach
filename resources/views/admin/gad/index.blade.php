@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Gender & Development</li>
@endsection

@section('page-title', 'Gender & Development Management')
@section('page-subtitle', 'Manage Gender and Development records and programs')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Gender & Development Records</h1>
                <p class="text-muted mb-0">Manage GAD programs and track beneficiaries</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.gad.reports') }}" class="btn btn-info mr-2">
                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                </a>
                <a href="{{ route('admin.gad.archived') }}" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-archive mr-2"></i>View Archived
                </a>
                <a href="{{ route('admin.gad.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add New GAD Record
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">GAD Records</strong>
            </div>
            <div class="card-body">
                @if($gadRecords->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="gadTable" data-export-title="GAD Records">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Gender Identity</th>
                                <th>Programs</th>
                                <th>Status</th>
                                <th>Special Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gadRecords as $gad)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($gad->resident && $gad->resident->photo)
                                                <img src="{{ $gad->resident->photo_url }}" alt="{{ $gad->resident->full_name }}" class="avatar-img rounded-circle">
                                            @else
                                                <div class="avatar-letter rounded-circle bg-primary">{{ $gad->resident ? substr($gad->resident->first_name, 0, 1) : 'U' }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $gad->resident->full_name ?? 'Unknown Resident' }}</strong>
                                            <br><small class="text-muted">ID: {{ $gad->resident->barangay_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $gad->gender_identity }}</td>
                                <td>
                                    @if($gad->programs_enrolled)
                                        <div class="program-badges">
                                        @foreach($gad->programs_enrolled as $program)
                                            <span class="badge badge-soft-info">{{ $program }}</span>
                                        @endforeach
                                        </div>
                                    @else
                                        <span class="badge badge-soft-secondary">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($gad->program_status)
                                        <span class="badge badge-soft-{{ $gad->program_status == 'Active' ? 'success' : ($gad->program_status == 'Completed' ? 'primary' : 'warning') }}">
                                            {{ $gad->program_status }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($gad->is_pregnant)
                                        <span class="badge badge-soft-warning">Pregnant</span>
                                    @endif
                                    @if($gad->is_solo_parent)
                                        <span class="badge badge-soft-success">Solo Parent</span>
                                    @endif
                                    @if($gad->is_vaw_case)
                                        <span class="badge badge-soft-danger">VAW Case</span>
                                    @endif
                                    @if(!$gad->is_pregnant && !$gad->is_solo_parent && !$gad->is_vaw_case)
                                        <span class="badge badge-soft-secondary">None</span>
                                    @endif
                                </td>
                                <td class="text-center table-actions-col">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.gad.show', $gad->id) }}">
                                                <i class="fas fa-eye mr-2 text-info"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.gad.edit', $gad->id) }}">
                                                <i class="fas fa-edit mr-2 text-primary"></i>Edit Record
                                            </a>
                                            @if($gad->resident)
                                            <a class="dropdown-item" href="{{ route('admin.residents.edit', $gad->resident) }}">
                                                <i class="fas fa-user mr-2 text-success"></i>View Resident
                                            </a>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-warning" href="#" onclick="event.preventDefault(); confirmArchive('{{ $gad->id }}', '{{ $gad->resident ? $gad->resident->full_name : 'this record' }}')">
                                                <i class="fas fa-archive mr-2"></i>Archive
                                            </a>
                                        </div>
                                    </div>
                                    <form id="delete-form-{{ $gad->id }}" action="{{ route('admin.gad.destroy', $gad->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Resident</th>
                                <th>Gender Identity</th>
                                <th>Programs</th>
                                <th>Status</th>
                                <th>Special Status</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @else
                <div class="text-center py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="text-center">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No GAD records found</h5>
                            <p class="text-muted">No GAD records have been created yet.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.gad.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Add New GAD Record
                    </a>
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
            DataTableHelpers.initDataTable('#gadTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 0, 'asc' ]] // Sort by resident name ascending
            });
        }
    });

    // Confirmation function
    function confirmArchive(gadId, residentName) {
        Swal.fire({
            title: 'Archive GAD Record?',
            text: `Are you sure you want to archive the GAD record for ${residentName}? The record will be moved to the archive and can be restored later.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-archive mr-2"></i>Yes, Archive',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${gadId}`).submit();
            }
        });
    }
</script>
@endpush