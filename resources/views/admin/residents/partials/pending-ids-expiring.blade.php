@php
// This partial expects $expiringSoon to be passed in
@endphp
<div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fe fe-alert-triangle fe-16 mr-2"></i>Expiring Soon</h4>
            <p class="text-muted mb-0">IDs expiring within 3 months</p>
        </div>
    </div>
</div>
<div class="card-body">
    @include('admin.residents.pending-ids-filter-expiring', ['expiringSoon' => $expiringSoon])
</div> 