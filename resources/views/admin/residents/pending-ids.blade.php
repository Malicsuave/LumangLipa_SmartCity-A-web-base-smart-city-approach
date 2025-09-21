@extends('layouts.admin.master')


@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">ID Card Management</h1>
                <p class="text-muted mb-0">Manage resident ID card issuance and renewals</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.residents.index') }}" class="btn btn-primary">
                    <i class="fas fa-users mr-2"></i>
                    All Residents
                </a>
                <a href="{{ route('admin.residents.bulk-upload') }}" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-upload mr-2"></i>
                    Bulk Upload
                </a>
            </div>
        </div>

        <!-- Information Alert about Senior Citizens -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fas fa-info-circle mr-3"></i>
            <div>
                <strong>Note:</strong> Senior citizens (60+ years old) have their own dedicated ID management system. 
                You can manage senior citizen IDs through the 
                <a href="{{ route('admin.senior-citizens.index') }}" class="alert-link">Senior Citizens section</a>.
            </div>
        </div>
    </div>
</div>
<!-- Tab Navigation -->
@php
    $tab = request('tab', 'issuance');
@endphp
<ul class="nav nav-tabs mb-4" id="idTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'issuance' ? 'active' : '' }}" href="?tab=issuance">Pending Issuance</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'renewal' ? 'active' : '' }}" href="?tab=renewal">Pending Renewal</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'expiring' ? 'active' : '' }}" href="?tab=expiring">Expiring Soon</a>
    </li>
</ul>
<div class="tab-content" id="idTabContent">
    <div class="tab-pane fade {{ $tab === 'issuance' ? 'show active' : '' }}" id="issuance" role="tabpanel">
        @include('admin.residents.partials.pending-ids-issuance', compact('pendingIssuance'))
    </div>
    <div class="tab-pane fade {{ $tab === 'renewal' ? 'show active' : '' }}" id="renewal" role="tabpanel">
        @include('admin.residents.partials.pending-ids-renewal', compact('pendingRenewal'))
    </div>
    <div class="tab-pane fade {{ $tab === 'expiring' ? 'show active' : '' }}" id="expiring" role="tabpanel">
        @include('admin.residents.partials.pending-ids-expiring', compact('expiringSoon'))
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')

<script>
$(function () {
    // DataTables for each tab
    DataTableHelpers.initDataTable('#issuanceTable', {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
    DataTableHelpers.initDataTable('#renewalTable', {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
    DataTableHelpers.initDataTable('#expiringTable', {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
    // Search bar for each tab
    $('#searchIssuanceInput').on('keyup', function() {
        $('#issuanceTable').DataTable().search($(this).val()).draw();
    });
    $('#searchRenewalInput').on('keyup', function() {
        $('#renewalTable').DataTable().search($(this).val()).draw();
    });
    $('#searchExpiringInput').on('keyup', function() {
        $('#expiringTable').DataTable().search($(this).val()).draw();
    });
    // Export buttons for each tab
    $('#exportIssuanceBtn').on('click', function() {
        $('#issuanceTable').DataTable().button('.buttons-csv').trigger();
    });
    $('#exportRenewalBtn').on('click', function() {
        $('#renewalTable').DataTable().button('.buttons-csv').trigger();
    });
    $('#exportExpiringBtn').on('click', function() {
        $('#expiringTable').DataTable().button('.buttons-csv').trigger();
    });
});
</script>
@endpush