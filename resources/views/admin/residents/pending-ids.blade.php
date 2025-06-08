@extends('layouts.admin.master')

@section('title', 'Pending ID Cards')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">Resident ID Card Management</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-primary">
                        <i class="fe fe-users"></i> All Residents
                    </a>
                </div>
            </div>
            
            <!-- Pending Issuance -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Pending Issuance</strong>
                    <p class="card-text text-muted">Residents with photos uploaded but no ID issued</p>
                </div>
                <div class="card-body">
                    @if($pendingIssuance->count() > 0)
                        <form action="{{ route('admin.residents.id.batch-issue') }}" method="POST" id="batchIssueForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAllPending">
                                                    <label class="custom-control-label" for="selectAllPending"></label>
                                                </div>
                                            </th>
                                            <th>Barangay ID</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingIssuance as $resident)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input resident-checkbox" id="resident{{ $resident->id }}" name="resident_ids[]" value="{{ $resident->id }}">
                                                        <label class="custom-control-label" for="resident{{ $resident->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $resident->barangay_id }}</td>
                                                <td>
                                                    @if($resident->photo)
                                                        <img src="{{ $resident->photo_url }}" alt="Resident Photo" class="avatar-img rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <span class="fe fe-user text-muted"></span>
                                                    @endif
                                                </td>
                                                <td>{{ $resident->full_name }}</td>
                                                <td>{{ $resident->age }}</td>
                                                <td>
                                                    @if($resident->id_status == 'not_issued')
                                                        <span class="badge badge-secondary">Not Issued</span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.residents.id.show', $resident) }}" class="btn btn-sm btn-primary">
                                                        <i class="fe fe-edit"></i> Manage ID
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success" id="batchIssueBtn" disabled>
                                    <i class="fe fe-check-circle"></i> Issue Selected ID Cards
                                </button>
                                <span class="text-muted ml-2" id="selectedCount"></span>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info"></i> No residents are currently pending ID issuance.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Needs Renewal -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Needs Renewal</strong>
                    <p class="card-text text-muted">Residents with IDs marked for renewal</p>
                </div>
                <div class="card-body">
                    @if($pendingRenewal->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Barangay ID</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Previous Issue Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRenewal as $resident)
                                        <tr>
                                            <td>{{ $resident->barangay_id }}</td>
                                            <td>
                                                @if($resident->photo)
                                                    <img src="{{ $resident->photo_url }}" alt="Resident Photo" class="avatar-img rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <span class="fe fe-user text-muted"></span>
                                                @endif
                                            </td>
                                            <td>{{ $resident->full_name }}</td>
                                            <td>{{ $resident->age }}</td>
                                            <td>{{ $resident->id_issued_at ? $resident->id_issued_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.residents.id.show', $resident) }}" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-refresh-cw"></i> Process Renewal
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info"></i> No residents are currently pending ID renewal.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Expiring Soon -->
            <div class="card shadow">
                <div class="card-header">
                    <strong class="card-title">Expiring Soon</strong>
                    <p class="card-text text-muted">IDs expiring within the next 3 months</p>
                </div>
                <div class="card-body">
                    @if($expiringSoon->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Barangay ID</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Expiry Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringSoon as $resident)
                                        <tr>
                                            <td>{{ $resident->barangay_id }}</td>
                                            <td>
                                                @if($resident->photo)
                                                    <img src="{{ $resident->photo_url }}" alt="Resident Photo" class="avatar-img rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <span class="fe fe-user text-muted"></span>
                                                @endif
                                            </td>
                                            <td>{{ $resident->full_name }}</td>
                                            <td>{{ $resident->age }}</td>
                                            <td>
                                                {{ $resident->id_expires_at ? $resident->id_expires_at->format('M d, Y') : 'N/A' }}
                                                @if($resident->id_expires_at && $resident->id_expires_at->isPast())
                                                    <span class="badge badge-danger ml-2">Expired</span>
                                                @elseif($resident->id_expires_at && $resident->id_expires_at->diffInDays(now()) <= 30)
                                                    <span class="badge badge-danger ml-2">Expires in {{ $resident->id_expires_at->diffInDays(now()) }} days</span>
                                                @else
                                                    <span class="badge badge-warning ml-2">Expires soon</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.residents.id.show', $resident) }}" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-edit"></i> Manage ID
                                                </a>
                                                <form action="{{ route('admin.residents.id.mark-renewal', $resident) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        <i class="fe fe-refresh-cw"></i> Mark for Renewal
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info"></i> No resident IDs are expiring soon.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Checkbox selection functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllPending');
        const checkboxes = document.querySelectorAll('.resident-checkbox');
        const batchIssueBtn = document.getElementById('batchIssueBtn');
        const selectedCountSpan = document.getElementById('selectedCount');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const isChecked = this.checked;
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                
                updateButtonState();
            });
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonState);
        });
        
        function updateButtonState() {
            const checkedCount = document.querySelectorAll('.resident-checkbox:checked').length;
            
            if (checkedCount > 0) {
                batchIssueBtn.disabled = false;
                selectedCountSpan.textContent = `(${checkedCount} selected)`;
            } else {
                batchIssueBtn.disabled = true;
                selectedCountSpan.textContent = '';
            }
            
            // Update select all checkbox
            if (selectAll) {
                selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            }
        }
    });
</script>
@endpush