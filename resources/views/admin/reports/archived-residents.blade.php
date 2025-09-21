@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.residents.archived') }}">Archived</a></li>
<li class="breadcrumb-item active" aria-current="page">Reports</li>
@endsection

@section('page-title', 'Archived Residents Report')
@section('page-subtitle', 'Generate comprehensive reports for archived residents')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Archived Residents Report</h1>
                <p class="text-muted mb-0">Comprehensive report of all archived residents</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.residents.archived') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Archived
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="card-title">Archived Residents Report Data</strong>
                    <div>
                        <span class="badge badge-warning">{{ $archivedResidents->count() }} Total Archived</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($archivedResidents->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="archivedReportTable" data-export-title="Archived Residents Report">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Age/Gender</th>
                                <th>Civil Status</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Date Registered</th>
                                <th>Date Archived</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedResidents as $resident)
                                <tr>
                                    <td><strong>{{ $resident->barangay_id }}</strong></td>
                                    <td>{{ $resident->last_name }}, {{ $resident->first_name }} {{ $resident->middle_name ? substr($resident->middle_name, 0, 1) . '.' : '' }} {{ $resident->suffix }}</td>
                                    <td>{{ $resident->type_of_resident }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                        <br><small class="text-muted">{{ $resident->sex }}</small>
                                    </td>
                                    <td>{{ $resident->civil_status }}</td>
                                    <td>{{ $resident->contact_number ?: 'N/A' }}</td>
                                    <td>{{ $resident->address }}</td>
                                    <td>
                                        {{ $resident->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $resident->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        {{ $resident->deleted_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $resident->deleted_at->diffForHumans() }}</small>
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
                                <th>Civil Status</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Date Registered</th>
                                <th>Date Archived</th>
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
                            <p class="text-muted">There are no archived residents to generate a report.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script>
    // Initialize DataTable
    document.addEventListener('DOMContentLoaded', function() {
        if (window.DataTableHelpers) {
            DataTableHelpers.initDataTable('#archivedReportTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 8, 'desc' ]] // Sort by archived date descending
            });
        }
    });
</script>
@endpush
