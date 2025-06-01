@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Residents</li>
@endsection

@section('page-title', 'Residents Management')
@section('page-subtitle', 'View and manage barangay residents')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Residents Management</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="card-title mb-0">Search Residents</h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addResidentModal">
                            <i class="fe fe-plus fe-16 mr-2"></i> Add New Resident
                        </button>
                    </div>
                </div>
                <hr>
                <form action="" class="mt-2">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="searchName">Name</label>
                                <input type="text" id="searchName" class="form-control" placeholder="Search by name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="searchAddress">Address</label>
                                <input type="text" id="searchAddress" class="form-control" placeholder="Address">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="searchCategory">Category</label>
                                <select id="searchCategory" class="form-control">
                                    <option value="">All Categories</option>
                                    <option value="senior">Senior Citizen</option>
                                    <option value="pwd">PWD</option>
                                    <option value="youth">Youth</option>
                                    <option value="household">Household Head</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-secondary btn-block">
                                    <i class="fe fe-search fe-16 mr-2"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Residents Directory</strong>
                <div class="float-right">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fe fe-download fe-16 mr-2"></i>Export
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fe fe-printer fe-16 mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>Age</th>
                                <th>Category</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1001</td>
                                <td>Juan Dela Cruz</td>
                                <td>123 Sampaguita St., Brgy. Lumanglipa</td>
                                <td>09123456789</td>
                                <td>42</td>
                                <td>
                                    <span class="badge badge-info">Household Head</span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-1001" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-1001">
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit-2 fe-16 mr-2 text-secondary"></i>Edit
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-file-text fe-16 mr-2 text-info"></i>Issue Document
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>1002</td>
                                <td>Maria Santos</td>
                                <td>456 Ilang-Ilang Ave., Brgy. Lumanglipa</td>
                                <td>09187654321</td>
                                <td>65</td>
                                <td>
                                    <span class="badge badge-warning">Senior Citizen</span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-1002" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-1002">
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit-2 fe-16 mr-2 text-secondary"></i>Edit
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-file-text fe-16 mr-2 text-info"></i>Issue Document
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>1003</td>
                                <td>Pedro Reyes</td>
                                <td>789 Rosal St., Brgy. Lumanglipa</td>
                                <td>09198765432</td>
                                <td>35</td>
                                <td>
                                    <span class="badge badge-success">PWD</span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-1003" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-1003">
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit-2 fe-16 mr-2 text-secondary"></i>Edit
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-file-text fe-16 mr-2 text-info"></i>Issue Document
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>1004</td>
                                <td>Ana Reyes</td>
                                <td>789 Rosal St., Brgy. Lumanglipa</td>
                                <td>09156789012</td>
                                <td>18</td>
                                <td>
                                    <span class="badge badge-primary">Youth</span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-1004" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-1004">
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit-2 fe-16 mr-2 text-secondary"></i>Edit
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-file-text fe-16 mr-2 text-info"></i>Issue Document
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>1005</td>
                                <td>Roberto Lim</td>
                                <td>101 Dahlia St., Brgy. Lumanglipa</td>
                                <td>09178901234</td>
                                <td>57</td>
                                <td>
                                    <span class="badge badge-info">Household Head</span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-1005" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-1005">
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit-2 fe-16 mr-2 text-secondary"></i>Edit
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-file-text fe-16 mr-2 text-info"></i>Issue Document
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="row mt-4">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite">
                            Showing 1 to 5 of 125 entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers">
                            <ul class="pagination justify-content-end">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Resident Modal -->
<div class="modal fade" id="addResidentModal" tabindex="-1" role="dialog" aria-labelledby="addResidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResidentModalLabel">Add New Resident</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birthdate">Birth Date</label>
                                <input type="date" class="form-control" id="birthdate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Complete Address</label>
                        <input type="text" class="form-control" id="address" placeholder="House No., Street, Barangay">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" placeholder="e.g., 09123456789">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="email@example.com">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="civilStatus">Civil Status</label>
                                <select class="form-control" id="civilStatus">
                                    <option value="">Select Civil Status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" id="occupation" placeholder="Occupation">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Categories (Select all that apply)</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="categoryHousehold">
                            <label class="custom-control-label" for="categoryHousehold">Household Head</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="categorySenior">
                            <label class="custom-control-label" for="categorySenior">Senior Citizen</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="categoryPWD">
                            <label class="custom-control-label" for="categoryPWD">PWD</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="categorySingleParent">
                            <label class="custom-control-label" for="categorySingleParent">Single Parent</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="categoryYouth">
                            <label class="custom-control-label" for="categoryYouth">Youth</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="photoUpload">Upload Photo</label>
                        <input type="file" class="form-control-file" id="photoUpload">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Resident</button>
            </div>
        </div>
    </div>
</div>
@endsection