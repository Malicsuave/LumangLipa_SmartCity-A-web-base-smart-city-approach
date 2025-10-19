@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Senior Citizen ID Card Management</h1>
                <p class="text-muted mb-0">Manage senior citizen ID card issuance and renewals</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-primary">
                    <i class="fas fa-users mr-2"></i>
                    All Senior Citizens
                </a>
                <a href="{{ route('admin.senior-citizens.register') }}" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-user-plus mr-2"></i>
                    Register Senior Citizen
                </a>
            </div>
        </div>

        <!-- Information Alert about Regular Residents -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fas fa-info-circle mr-3"></i>
            <div>
                <strong>Note:</strong> Regular residents (under 60 years old) have their own dedicated ID management system. 
                You can manage resident IDs through the 
                <a href="{{ route('admin.residents.id.pending') }}" class="alert-link">Residents ID Management section</a>.
            </div>
        </div>
    </div>
</div>
<!-- Tab Navigation -->
@php
    $tab = request('tab', 'issued');
@endphp
<ul class="nav nav-tabs mb-4" id="idTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'issued' ? 'active' : '' }}" href="?tab=issued">Issued IDs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'renewal' ? 'active' : '' }}" href="?tab=renewal">Pending Renewal</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'expiring' ? 'active' : '' }}" href="?tab=expiring">Expiring Soon</a>
    </li>
</ul>
<div class="tab-content" id="idTabContent">
    <div class="tab-pane fade {{ $tab === 'issued' ? 'show active' : '' }}" id="issued" role="tabpanel">
        @include('admin.senior-citizens.partials.issued-ids', compact('issuedIds'))
    </div>
    <div class="tab-pane fade {{ $tab === 'renewal' ? 'show active' : '' }}" id="renewal" role="tabpanel">
        @include('admin.senior-citizens.partials.pending-ids-renewal', compact('pendingRenewal'))
    </div>
    <div class="tab-pane fade {{ $tab === 'expiring' ? 'show active' : '' }}" id="expiring" role="tabpanel">
        @include('admin.senior-citizens.partials.pending-ids-expiring', compact('expiringSoon'))
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')

<script>
$(function () {
    // Destroy existing DataTable instances if they exist
    if ($.fn.DataTable.isDataTable('#issuedTable')) {
        $('#issuedTable').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#renewalTable')) {
        $('#renewalTable').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#expiringTable')) {
        $('#expiringTable').DataTable().destroy();
    }

    // DataTables for each tab
    DataTableHelpers.initDataTable('#issuedTable', {
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
    $('#searchIssuedInput').on('keyup', function() {
        $('#issuedTable').DataTable().search($(this).val()).draw();
    });
    $('#searchRenewalInput').on('keyup', function() {
        $('#renewalTable').DataTable().search($(this).val()).draw();
    });
    $('#searchExpiringInput').on('keyup', function() {
        $('#expiringTable').DataTable().search($(this).val()).draw();
    });
    // Export buttons for each tab
    $('#exportIssuedBtn').on('click', function() {
        $('#issuedTable').DataTable().button('.buttons-csv').trigger();
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
