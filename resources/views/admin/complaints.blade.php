@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Complaints Management</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Manage Complaints</strong>
                <div class="float-right">
                    <button class="btn btn-sm btn-primary">
                        <i class="fe fe-plus-circle fe-12 mr-2"></i>New Complaint
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-4">This is the complaints management module for Barangay Captain and Complaint Managers.</p>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-1">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-danger metric-icon">
                                            <i class="fe fe-alert-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Open</p>
                                        <span class="h3 metric-counter">5</span>
                                        <span class="small text-muted">Complaints</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning metric-icon">
                                            <i class="fe fe-clock text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">In Progress</p>
                                        <span class="h3 metric-counter">8</span>
                                        <span class="small text-muted">Complaints</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success metric-icon">
                                            <i class="fe fe-check-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Resolved</p>
                                        <span class="h3 metric-counter">24</span>
                                        <span class="small text-muted">Complaints</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mt-3 mb-4">Recent Complaints</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Complainant</th>
                                        <th>Subject</th>
                                        <th>Date Filed</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>Noise Complaint</td>
                                        <td>May 5, 2025</td>
                                        <td><span class="badge badge-danger">Open</span></td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-clock fe-16 mr-2 text-warning"></i>Process
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-users fe-16 mr-2 text-info"></i>Schedule Meeting
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#">
                                                        <i class="fe fe-x-circle fe-16 mr-2"></i>Dismiss
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Maria Santos</td>
                                        <td>Illegal Construction</td>
                                        <td>May 3, 2025</td>
                                        <td><span class="badge badge-warning">In Progress</span></td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Resolve
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-file-text fe-16 mr-2 text-secondary"></i>Add Note
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Pedro Reyes</td>
                                        <td>Water Supply Issue</td>
                                        <td>May 1, 2025</td>
                                        <td><span class="badge badge-success">Resolved</span></td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-printer fe-16 mr-2 text-secondary"></i>Print Report
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-refresh-cw fe-16 mr-2 text-warning"></i>Reopen
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Table Paging" class="my-3">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize datatable if needed
        if ($.fn.dataTable) {
            $('.data-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "pageLength": 10
            });
        }
    });
</script>
@endsection