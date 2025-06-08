@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Residents</li>
@endsection

@section('page-title', 'Resident Management')
@section('page-subtitle', 'Manage barangay residents information')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-users fe-16 mr-2"></i>All Residents</h4>
                        <p class="text-muted mb-0">Manage all registered residents in your barangay</p>
                    </div>
                    <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
                        <i class="fe fe-user-plus fe-16 mr-2"></i>Register New Resident
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.residents.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search for resident..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-control" onchange="this.form.submit()">
                                <option value="">All Resident Types</option>
                                <option value="Non-Migrant" {{ request('type') == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                <option value="Migrant" {{ request('type') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                <option value="Transient" {{ request('type') == 'Transient' ? 'selected' : '' }}>Transient</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="civil_status" class="form-control" onchange="this.form.submit()">
                                <option value="">All Civil Status</option>
                                <option value="Single" {{ request('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ request('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ request('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Separated" {{ request('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                <option value="Divorced" {{ request('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                    </div>
                </form>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($residents->count())
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>Barangay ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Civil Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($residents as $resident)
                            <tr>
                                <td>{{ $resident->barangay_id }}</td>
                                <td>
                                    {{ $resident->last_name }}, {{ $resident->first_name }} 
                                    {{ $resident->middle_name ? substr($resident->middle_name, 0, 1) . '.' : '' }}
                                    {{ $resident->suffix }}
                                </td>
                                <td>{{ $resident->type_of_resident }}</td>
                                <td>{{ $resident->civil_status }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted sr-only">Action</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('admin.residents.show', $resident) }}" class="dropdown-item">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            <a href="{{ route('admin.residents.edit', $resident) }}" class="dropdown-item">
                                                <i class="fe fe-edit fe-16 mr-2 text-info"></i>Edit
                                            </a>
                                            <a href="#" class="dropdown-item text-danger" 
                                               onclick="event.preventDefault(); confirmDelete('{{ $resident->id }}', '{{ $resident->first_name }} {{ $resident->last_name }}')">
                                                <i class="fe fe-trash fe-16 mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Form -->
                                    <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $residents->links() }}
                </div>
                
                @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" alt="No residents found" class="img-fluid mb-3" width="200">
                    <h4>No residents found</h4>
                    <p class="text-muted">
                        @if(request('search') || request('type') || request('civil_status'))
                            No residents match your search criteria. <a href="{{ route('admin.residents.index') }}">Clear all filters</a>
                        @else
                            There are no residents registered yet. Start by adding a new resident.
                        @endif
                    </p>
                    <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
                        <i class="fe fe-user-plus fe-16 mr-2"></i>Register New Resident
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Resident</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the record for <strong id="residentName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        document.getElementById('residentName').textContent = name;
        document.getElementById('confirmDelete').addEventListener('click', function() {
            document.getElementById('delete-form-' + id).submit();
        });
        $('#deleteModal').modal('show');
    }
</script>
@endsection