@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Health Services</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Manage Health Services</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">This is the health services management module for Barangay Captain and Health Workers.</p>
                
                <!-- This is a placeholder for actual health services functionality -->
                
                
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-1">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary metric-icon">
                                            <i class="fe fe-users text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Vaccination</p>
                                        <span class="h3 metric-counter">124</span>
                                        <span class="small text-muted">Residents</span>
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
                                            <i class="fe fe-heart text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Checkups</p>
                                        <span class="h3 metric-counter">45</span>
                                        <span class="small text-muted">This month</span>
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
                                            <i class="fe fe-activity text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Medicine</p>
                                        <span class="h3 metric-counter">84</span>
                                        <span class="small text-muted">Distributed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mt-3 mb-4">Recent Health Services</h5>
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Resident</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Juan Dela Cruz</td>
                                    <td>Blood Pressure Check</td>
                                    <td>May 5, 2025</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-health1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-vertical fe-16"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-health1">
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-file-text fe-16 mr-2 text-secondary"></i>Medical Record
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-calendar fe-16 mr-2 text-info"></i>Schedule Follow-up
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Maria Santos</td>
                                    <td>Vaccination - COVID-19</td>
                                    <td>May 4, 2025</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-health2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-vertical fe-16"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-health2">
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-file-text fe-16 mr-2 text-secondary"></i>Medical Record
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-printer fe-16 mr-2 text-info"></i>Print Certificate
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pedro Reyes</td>
                                    <td>Medical Consultation</td>
                                    <td>May 3, 2025</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-health3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-vertical fe-16"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-health3">
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Complete
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-message-circle fe-16 mr-2 text-info"></i>Send Reminder
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">
                                                    <i class="fe fe-x-circle fe-16 mr-2"></i>Cancel
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