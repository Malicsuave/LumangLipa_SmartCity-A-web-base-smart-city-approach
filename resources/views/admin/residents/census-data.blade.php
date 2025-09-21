@extends('layouts.admin.master')



@section('page-title', 'Census Data')
@section('page-subtitle', 'Manage census and household records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Census Data</h1>
                <p class="text-muted mb-0">Manage census and household records</p>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>Add Census Record
                </a>
            </div>
        </div>
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Census Data</strong>
            </div>
            <div class="card-body">
                <!-- Search and Filter removed per request -->

                <!-- Census Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="censusTable" data-export-title="Census Data">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Household Info</th>
                                <th>Date Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example row -->
                            <tr>
                                <td>Juan Dela Cruz</td>
                                <td>Purok 1, Brgy. Example</td>
                                <td>5 members</td>
                                <td>2025-07-01</td>
                                <td class="text-center table-actions-col">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="fas fa-edit mr-2"></i>Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash-alt mr-2"></i>Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Household Info</th>
                                <th>Date Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Pagination (if needed) -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.DataTableHelpers) {
        DataTableHelpers.initDataTable('#censusTable', {
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            order: [[ 0, 'desc' ]]
        });
    }
});
</script>
@endpush