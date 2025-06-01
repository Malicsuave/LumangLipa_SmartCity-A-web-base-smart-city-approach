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
            </div>
            <div class="card-body">
                <p class="mb-4">This is the complaints management module for Barangay Captain and Complaint Managers.</p>
                
                
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-danger">
                                            <i class="fe fe-alert-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Open</p>
                                        <span class="h3">5</span>
                                        <span class="small text-muted">Complaints</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning">
                                            <i class="fe fe-clock text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">In Progress</p>
                                        <span class="h3">8</span>
                                        <span class="small text-muted">Complaints</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success">
                                            <i class="fe fe-check-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Resolved</p>
                                        <span class="h3">24</span>
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
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-comp1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-vertical fe-16"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-comp1">
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
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-comp2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-vertical fe-16"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-comp2">
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
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-comp3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-vertical fe-16"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-comp3">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection