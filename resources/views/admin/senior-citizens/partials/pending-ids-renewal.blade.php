@php
// This partial expects $pendingRenewal to be passed in
@endphp
<div class="card shadow-lg border-0 admin-card-shadow mb-4">
    <div class="card-header" style="background: #3498db; color: white; border-radius: 12px 12px 0 0 !important; border: none;">
        <strong class="card-title">Pending Senior Citizen ID Renewal</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(isset($pendingRenewal) && $pendingRenewal->count() > 0)
                <table class="table table-bordered table-striped" id="renewalTable" data-export-title="Pending Senior Citizen ID Renewal">
                    <thead>
                        <tr>
                            <th>Senior ID</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Renewal/Expiry Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRenewal as $senior)
                            <tr>
                                <td><strong>{{ $senior->senior_id_number }}</strong></td>
                                <td>
                                    <strong>{{ $senior->last_name }}, {{ $senior->first_name }}</strong>
                                    @if($senior->middle_name)
                                        {{ substr($senior->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $senior->suffix }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($senior->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $senior->sex }}</small>
                                </td>
                                <td>
                                    @if($senior->senior_id_expires_at)
                                        {{ $senior->senior_id_expires_at->format('M d, Y') }}
                                        <br><small class="text-muted">
                                            @if($senior->senior_id_expires_at->isPast())
                                                Expired
                                            @else
                                                Expires
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id-management', $senior) }}">
                                                <i class="fas fa-id-card mr-2"></i>Manage ID
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to issue a new ID for this senior citizen?')){ document.getElementById('renew-{{ $senior->id }}').submit(); }">
                                                <i class="fas fa-check-circle mr-2"></i>Issue New ID (Renew)
                                            </a>
                                            <form id="renew-{{ $senior->id }}" action="{{ route('admin.senior-citizens.issue-id', $senior) }}" method="POST" style="display:none;">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id.preview', $senior) }}">
                                                <i class="fas fa-image mr-2"></i>Preview ID
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id.download', $senior) }}">
                                                <i class="fas fa-download mr-2"></i>Download ID
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to revoke this senior citizen\'s ID? This action cannot be undone.')){ document.getElementById('revoke-{{ $senior->id }}').submit(); }">
                                                <i class="fas fa-times-circle mr-2"></i>Revoke ID
                                            </a>
                                            <form id="revoke-{{ $senior->id }}" action="{{ route('admin.senior-citizens.revoke-id', $senior) }}" method="POST" style="display:none;">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Remove this senior citizen from the renewal queue?')){ document.getElementById('remove-renewal-{{ $senior->id }}').submit(); }">
                                                <i class="fas fa-minus-circle mr-2"></i>Remove from Renewal Queue
                                            </a>
                                            <form id="remove-renewal-{{ $senior->id }}" action="{{ route('admin.senior-citizens.remove-renewal', $senior) }}" method="POST" style="display:none;">
                                                @csrf
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
                    <div class="d-flex justify-content-center mb-3">
                        <span style="display:inline-block;width:120px;height:120px;border-radius:50%;background:#f3f4f6;border:4px solid #e5e7eb;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-sync-alt" style="font-size: 2rem; color: #bdbdbd;"></i>
                        </span>
                    </div>
                    <h4>No senior citizens pending ID renewal</h4>
                    <p class="text-muted">All senior citizen IDs are up to date.</p>
                </div>
            @endif
        </div>
    </div>
</div>
