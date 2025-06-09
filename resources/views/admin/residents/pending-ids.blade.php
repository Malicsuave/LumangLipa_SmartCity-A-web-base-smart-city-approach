@extends('layouts.admin')

@section('title', 'ID Card Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">ID Card Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
        <li class="breadcrumb-item active">ID Card Management</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-id-card me-1"></i>
                    Pending ID Issuance
                </div>
                <div class="card-body">
                    @if($pendingIssuance->count() > 0)
                        <form action="{{ route('admin.residents.id.batch-issue') }}" method="POST" id="batchIssueForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="pendingIssuanceTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Barangay ID</th>
                                            <th>Name</th>
                                            <th>Photo</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingIssuance as $resident)
                                            <tr>
                                                <td>
                                                    @if($resident->photo)
                                                        <input type="checkbox" name="resident_ids[]" value="{{ $resident->id }}" class="resident-checkbox">
                                                    @endif
                                                </td>
                                                <td>{{ $resident->barangay_id }}</td>
                                                <td>{{ $resident->full_name }}</td>
                                                <td class="text-center">
                                                    @if($resident->photo)
                                                        <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="id-photo-thumbnail">
                                                    @else
                                                        <span class="badge bg-warning text-dark">No Photo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($resident->id_status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($resident->id_status == 'not_issued')
                                                        <span class="badge bg-secondary">Not Issued</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($resident->photo)
                                                        <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-id-card"></i> Manage ID
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-camera"></i> Add Photo
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success mt-3" id="batchIssueBtn" disabled>
                                <i class="fas fa-id-card me-1"></i> Issue IDs for Selected Residents
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            No residents with pending ID issuance at this time.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-sync me-1"></i>
                    IDs Pending Renewal
                </div>
                <div class="card-body">
                    @if($pendingRenewal->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="pendingRenewalTable">
                                <thead>
                                    <tr>
                                        <th>Barangay ID</th>
                                        <th>Name</th>
                                        <th>Photo</th>
                                        <th>Issued Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRenewal as $resident)
                                        <tr>
                                            <td>{{ $resident->barangay_id }}</td>
                                            <td>{{ $resident->full_name }}</td>
                                            <td class="text-center">
                                                @if($resident->photo)
                                                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="id-photo-thumbnail">
                                                @else
                                                    <span class="badge bg-warning text-dark">No Photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $resident->id_issued_at ? $resident->id_issued_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-id-card"></i> Manage ID
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No residents with IDs pending renewal at this time.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Expiring Soon (Within 3 Months)
                </div>
                <div class="card-body">
                    @if($expiringSoon->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="expiringSoonTable">
                                <thead>
                                    <tr>
                                        <th>Barangay ID</th>
                                        <th>Name</th>
                                        <th>Photo</th>
                                        <th>Expiry Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringSoon as $resident)
                                        <tr>
                                            <td>{{ $resident->barangay_id }}</td>
                                            <td>{{ $resident->full_name }}</td>
                                            <td class="text-center">
                                                @if($resident->photo)
                                                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="id-photo-thumbnail">
                                                @else
                                                    <span class="badge bg-warning text-dark">No Photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $resident->id_expires_at ? $resident->id_expires_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.residents.id.show', $resident->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-id-card"></i> Manage ID
                                                </a>
                                                <a href="{{ route('admin.residents.id.mark-renewal', $resident->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-sync"></i> Mark for Renewal
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No resident IDs are expiring soon.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#pendingIssuanceTable, #pendingRenewalTable, #expiringSoonTable').DataTable({
            responsive: true
        });
        
        // Select all checkboxes
        $('#selectAll').click(function() {
            $('.resident-checkbox').prop('checked', $(this).prop('checked'));
            updateBatchButton();
        });
        
        // Update batch button state when individual checkboxes change
        $('.resident-checkbox').click(function() {
            updateBatchButton();
        });
        
        // Update the batch issue button state
        function updateBatchButton() {
            var checkedCount = $('.resident-checkbox:checked').length;
            $('#batchIssueBtn').prop('disabled', checkedCount === 0);
            $('#batchIssueBtn').text(checkedCount > 0 ? 
                'Issue IDs for ' + checkedCount + ' Selected Resident' + (checkedCount > 1 ? 's' : '') :
                'Issue IDs for Selected Residents');
        }
    });
</script>

<style>
    .id-photo-thumbnail {
        max-width: 50px;
        max-height: 50px;
        border-radius: 4px;
    }
</style>
@endsection