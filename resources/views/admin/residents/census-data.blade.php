
@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Census Data</li>
@endsection

@section('page-title', 'Census Data')
@section('page-subtitle', 'Manage census and household records')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-users fe-16 mr-2"></i>Census Data</h4>
                        <p class="text-muted mb-0">Manage all census and household records</p>
                    </div>
                    <div>
                        <a href="#" class="btn btn-primary">
                            <i class="fe fe-user-plus fe-16 mr-2"></i>Add Census Record
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form action="#" method="GET" id="filterForm">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" name="search" 
                                       placeholder="Search census by name, address, household..." 
                                       value="">
                                <div class="input-group-append">
                                    <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#filterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                                        <i class="fe fe-filter fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Add filter clear button if needed -->
                        </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter census records by various criteria</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Household Size</label>
                                        <input type="number" name="household_size" class="form-control form-control-sm" value="">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control form-control-sm" value="">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Date Recorded</label>
                                        <input type="date" name="date_recorded" class="form-control form-control-sm" value="">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Census Data Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-hover" id="censusTable">
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
                                    <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination (if needed) -->
            </div>
        </div>
    </div>
</div>
@endsection
