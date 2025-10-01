@php
// This partial expects $expiringSoon to be passed in
@endphp
<div class="card shadow-lg border-0 admin-card-shadow mb-4">
    <div class="card-header" style="background: #3498db; color: white; border-radius: 12px 12px 0 0 !important; border: none;">
        <strong class="card-title">Senior Citizen IDs Expiring Soon</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(isset($expiringSoon) && $expiringSoon->count() > 0)
                <table class="table table-bordered table-striped" id="expiringTable" data-export-title="Senior Citizen IDs Expiring Soon">
                    <thead>
                        <tr>
                            <th>Senior ID</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Expiry Date</th>
                            <th>Days Until Expiry</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiringSoon as $senior)
                            @php
                                $daysUntilExpiry = $senior->senior_id_expires_at ? now()->diffInDays($senior->senior_id_expires_at, false) : null;
                            @endphp
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
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($daysUntilExpiry !== null)
                                        @if($daysUntilExpiry < 0)
                                            <span class="badge badge-danger">Expired {{ abs($daysUntilExpiry) }} days ago</span>
                                        @elseif($daysUntilExpiry == 0)
                                            <span class="badge badge-warning">Expires today</span>
                                        @elseif($daysUntilExpiry <= 30)
                                            <span class="badge badge-warning">{{ $daysUntilExpiry }} days</span>
                                        @else
                                            <span class="badge badge-success">{{ $daysUntilExpiry }} days</span>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
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
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id.preview', $senior) }}">
                                                <i class="fas fa-image mr-2"></i>Preview ID
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id.download', $senior) }}">
                                                <i class="fas fa-download mr-2"></i>Download ID
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to revoke this senior citizen\'s ID? This action cannot be undone.')){ document.getElementById('revoke-expiring-{{ $senior->id }}').submit(); }">
                                                <i class="fas fa-times-circle mr-2"></i>Revoke ID
                                            </a>
                                            <form id="revoke-expiring-{{ $senior->id }}" action="{{ route('admin.senior-citizens.revoke-id', $senior) }}" method="POST" style="display:none;">
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
                            <i class="fas fa-calendar-check" style="font-size: 2rem; color: #bdbdbd;"></i>
                        </span>
                    </div>
                    <h4>No senior citizen IDs expiring soon</h4>
                    <p class="text-muted">All senior citizen IDs are valid for the foreseeable future.</p>
                </div>
            @endif
        </div>
    </div>
</div>
