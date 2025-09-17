@extends('layouts.admin.master')

@push('styles')
@include('admin.components.datatable-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">ID Card Management</h1>
                <p class="text-muted mb-0">Manage resident ID card issuance and renewals</p>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-primary mr-2" onclick="window.location.href='{{ route('admin.residents.bulk-upload') }}'">
                    <i class="fas fa-upload mr-2"></i>Bulk Upload
                </button>
                <a href="{{ route('admin.residents.add') }}" class="btn btn-success">
                    <i class="fas fa-plus mr-2"></i>Add New
                </a>
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
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>
<script>
$(function () {
    // DataTables for each tab
    DataTableHelpers.initDataTable('#issuance .table-responsive table', {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
    DataTableHelpers.initDataTable('#renewal .table-responsive table', {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
    DataTableHelpers.initDataTable('#expiring .table-responsive table', {
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        order: [[ 0, "desc" ]],
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ]
    });
    // Search bar for each tab
    $('#searchIssuanceInput').on('keyup', function() {
        $('#issuance .table-responsive table').DataTable().search($(this).val()).draw();
    });
    $('#searchRenewalInput').on('keyup', function() {
        $('#renewal .table-responsive table').DataTable().search($(this).val()).draw();
    });
    $('#searchExpiringInput').on('keyup', function() {
        $('#expiring .table-responsive table').DataTable().search($(this).val()).draw();
    });
    // Export buttons for each tab
    $('#exportIssuanceBtn').on('click', function() {
        $('#issuance .table-responsive table').DataTable().button('.buttons-csv').trigger();
    });
    $('#exportRenewalBtn').on('click', function() {
        $('#renewal .table-responsive table').DataTable().button('.buttons-csv').trigger();
    });
    $('#exportExpiringBtn').on('click', function() {
        $('#expiring .table-responsive table').DataTable().button('.buttons-csv').trigger();
    });
});
</script>
@endpush