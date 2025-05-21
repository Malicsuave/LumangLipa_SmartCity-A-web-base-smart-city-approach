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
                        <div class="card mb-4 shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-users text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Vaccination</p>
                                        <span class="h3">124</span>
                                        <span class="small text-muted">Residents</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning">
                                            <i class="fe fe-heart text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Checkups</p>
                                        <span class="h3">45</span>
                                        <span class="small text-muted">This month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success">
                                            <i class="fe fe-activity text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Medicine</p>
                                        <span class="h3">84</span>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Juan Dela Cruz</td>
                                    <td>Blood Pressure Check</td>
                                    <td>May 5, 2025</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td><button class="btn btn-sm btn-primary">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>Maria Santos</td>
                                    <td>Vaccination - COVID-19</td>
                                    <td>May 4, 2025</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td><button class="btn btn-sm btn-primary">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>Pedro Reyes</td>
                                    <td>Medical Consultation</td>
                                    <td>May 3, 2025</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td><button class="btn btn-sm btn-primary">View Details</button></td>
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