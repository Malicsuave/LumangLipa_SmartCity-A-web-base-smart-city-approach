@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Document Requests</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;">
            <div class="card-header">
                <strong class="card-title">Manage Document Requests</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">This is the document requests management module for Barangay Captain and Secretary.</p>
                
                <!-- This is a placeholder for actual document request functionality -->
               
                
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Resident</th>
                            <th>Document Type</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Juan Dela Cruz</td>
                            <td>Barangay Clearance</td>
                            <td>May 5, 2025</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-doc1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe fe-more-vertical fe-16"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-doc1">
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Approve
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-printer fe-16 mr-2 text-secondary"></i>Print
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="fe fe-x-circle fe-16 mr-2"></i>Reject
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Maria Santos</td>
                            <td>Certificate of Residency</td>
                            <td>May 4, 2025</td>
                            <td><span class="badge badge-success">Approved</span></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-doc2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe fe-more-vertical fe-16"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-doc2">
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-printer fe-16 mr-2 text-secondary"></i>Print
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-send fe-16 mr-2 text-info"></i>Email to Resident
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Pedro Reyes</td>
                            <td>Certificate of Indigency</td>
                            <td>May 3, 2025</td>
                            <td><span class="badge badge-success">Approved</span></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-doc3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe fe-more-vertical fe-16"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-doc3">
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-printer fe-16 mr-2 text-secondary"></i>Print
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fe fe-send fe-16 mr-2 text-info"></i>Email to Resident
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
@endsection