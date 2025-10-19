@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active" aria-current="page">Registrations</li>
@endsection

@section('title', 'Announcement Registrations')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Registrations for "{{ $announcement->title }}"</h1>
                <p class="text-muted mb-0">
                    Total registrations: {{ $announcement->current_slots }}
                    @if($announcement->max_slots) / {{ $announcement->max_slots }} slots @endif
                </p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Announcement
                </a>
                @if($announcement->current_slots > 0)
                <a href="{{ route('admin.announcements.export-registrations', $announcement) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-users"></i> Registration Details
                    <span class="badge badge-primary ml-2">{{ $announcement->current_slots }}</span>
                </strong>
            </div>
            <div class="card-body">
                @if($announcement->current_slots > 0)
                <table id="registrationsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Age</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $index => $registration)
                        <tr>
                            <td class="text-center"><strong>#{{ $registrations->firstItem() + $index }}</strong></td>
                            <td>
                                <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>
                                @if($registration->middle_name)
                                    <br><small class="text-muted">{{ $registration->middle_name }}</small>
                                @endif
                            </td>
                            <td>
                                @if($registration->phone)
                                    <div class="mb-1">
                                        <i class="fas fa-phone text-success"></i> {{ $registration->phone }}
                                    </div>
                                @endif
                                @if($registration->email)
                                    <div>
                                        <i class="fas fa-envelope text-info"></i> {{ $registration->email }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $registration->address }}</td>
                            <td class="text-center">{{ $registration->age }}</td>
                            <td class="text-center">{{ $registration->created_at->format('M j, Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('admin.announcement-registrations.destroy', $registration) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to remove this registration?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash"></i> Remove Registration
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No registrations yet</h5>
                    <p class="text-muted">No one has registered for this announcement yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($announcement->type === 'limited_slots' && $announcement->max_slots)
<div class="row mt-4">
    <div class="col-lg-4">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Slot Status</strong>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Available Slots</span>
                    <span class="badge badge-success">
                        {{ $announcement->max_slots - $announcement->current_slots }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Registered</span>
                    <span class="badge badge-primary">{{ $announcement->current_slots }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Slots</span>
                    <span class="badge badge-secondary">{{ $announcement->max_slots }}</span>
                </div>
                <div class="progress mb-2">
                    @php $percentage = ($announcement->current_slots / $announcement->max_slots) * 100; @endphp
                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                </div>
                <small class="text-muted">{{ round($percentage, 1) }}% filled</small>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
@include('admin.components.datatable-styles')
@endpush

@push('scripts')
@include('admin.components.datatable-scripts')
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>
<script>
$(function () {
    // Initialize DataTable for registrations table
    const registrationsTable = DataTableHelpers.initDataTable("#registrationsTable", {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 5, "desc" ]], // Sort by registration date (newest first)
        pageLength: 10,
        lengthChange: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        columnDefs: [
            { "orderable": false, "targets": -1 }, // Disable sorting on Actions column
            { "responsivePriority": 1, "targets": 0 }, // # column priority
            { "responsivePriority": 2, "targets": 1 }, // Name column priority
            { "responsivePriority": 3, "targets": 2 }, // Contact column priority
            { "responsivePriority": 4, "targets": 3 }, // Address column priority
            { "responsivePriority": 5, "targets": 4 }, // Age column priority
            { "responsivePriority": 6, "targets": 5 }, // Registered column priority
            { "responsivePriority": 10, "targets": -1 } // Actions column lowest priority
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});
</script>
@endpush