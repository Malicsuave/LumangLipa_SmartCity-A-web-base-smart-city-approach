@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Document Requests</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Juan Dela Cruz</td>
                            <td>Barangay Clearance</td>
                            <td>May 5, 2025</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-success">Approve</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Maria Santos</td>
                            <td>Certificate of Residency</td>
                            <td>May 4, 2025</td>
                            <td><span class="badge badge-success">Approved</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Pedro Reyes</td>
                            <td>Certificate of Indigency</td>
                            <td>May 3, 2025</td>
                            <td><span class="badge badge-success">Approved</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection