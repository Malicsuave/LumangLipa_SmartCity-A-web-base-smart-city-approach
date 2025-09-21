@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
<li class="breadcrumb-item active" aria-current="page">Reports</li>
@endsection

@section('page-title', 'Senior Citizens Report')
@section('page-subtitle', 'Generate comprehensive reports for senior citizens')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Senior Citizens Report</h1>
                <p class="text-muted mb-0">Comprehensive report of all senior citizens (60+ years old)</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Senior Citizens
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="card-title">Senior Citizens Report Data</strong>
                    <div>
                        <span class="badge badge-info">{{ $seniorCitizens->count() }} Total Senior Citizens</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($seniorCitizens->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="seniorCitizensReportTable" data-export-title="Senior Citizens Report">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Age/Gender</th>
                                <th>Civil Status</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>ID Status</th>
                                <th>Date Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seniorCitizens as $seniorCitizen)
                                <tr>
                                    <td><strong>{{ $seniorCitizen->resident->barangay_id }}</strong></td>
                                    <td>{{ $seniorCitizen->resident->last_name }}, {{ $seniorCitizen->resident->first_name }} {{ $seniorCitizen->resident->middle_name ? substr($seniorCitizen->resident->middle_name, 0, 1) . '.' : '' }} {{ $seniorCitizen->resident->suffix }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($seniorCitizen->resident->birthdate)->age }} years old
                                        <br><small class="text-muted">{{ $seniorCitizen->resident->sex }}</small>
                                    </td>
                                    <td>{{ $seniorCitizen->resident->civil_status }}</td>
                                    <td>{{ $seniorCitizen->resident->contact_number ?: 'N/A' }}</td>
                                    <td>{{ $seniorCitizen->resident->address }}</td>
                                    <td>
                                        @if($seniorCitizen->resident->photo && $seniorCitizen->resident->signature)
                                            <span class="badge badge-success">ID Ready</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $seniorCitizen->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $seniorCitizen->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Age/Gender</th>
                                <th>Civil Status</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>ID Status</th>
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
                            <h5 class="text-muted">No senior citizens found</h5>
                            <p class="text-muted">There are no senior citizens to generate a report.</p>
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
            DataTableHelpers.initDataTable('#seniorCitizensReportTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 7, 'desc' ]] // Sort by date registered descending
            });
        }
    });
</script>
@endpush
