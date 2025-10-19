@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Reports</li>
@endsection

@section('page-title', 'Residents Report')
@section('page-subtitle', 'Generate comprehensive reports for residents')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Residents Report</h1>
                <p class="text-muted mb-0">Comprehensive report of all active residents</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Residents
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="card-title">Residents Report Data</strong>
                    <div>
                        <span class="badge badge-info">{{ $residents->count() }} Total Residents</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($residents->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="residentsReportTable" data-export-title="Residents Report">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($residents as $resident)
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="text-center">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No residents found</h5>
                            <p class="text-muted">There are no residents to generate a report.</p>
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
            DataTableHelpers.initDataTable('#residentsReportTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 7, 'desc' ]] // Sort by date registered descending
            });
        }
    });
</script>
@endpush
