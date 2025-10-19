@php
// This partial expects $expiringSoon to be passed in
@endphp
<div class="card shadow-lg border-0 admin-card-shadow mb-4">
    <div class="card-header" style="background: #3498db; color: white; border-radius: 12px 12px 0 0 !important; border: none;">
        <strong class="card-title">Expiring Soon</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($expiringSoon->count() > 0)
                <table class="table table-bordered table-striped" id="expiringTable" data-export-title="Expiring IDs">
                    <thead>
                        <tr>
                            <th>Barangay ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Age/Gender</th>
                            <th>Expiry Date & Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiringSoon as $resident)
                            <tr>
                                <td><strong>{{ $resident->barangay_id }}</strong></td>
                                <td>
                                    <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                    @if($resident->middle_name)
                                        {{ substr($resident->middle_name, 0, 1) }}.
                                    @endif
                                    {{ $resident->suffix }}
                                </td>
                                <td>{{ $resident->type_of_resident }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $resident->sex }}</small>
                                </td>
                                <td>
                                    @if($resident->id_expires_at)
                                        {{ $resident->id_expires_at->format('M d, Y') }}
                                        <br><small class="text-muted">
                                            @php
                                                $daysLeft = \Carbon\Carbon::now()->diffInDays($resident->id_expires_at, false);
                                            @endphp
                                            @if($daysLeft > 30)
                                                {{ round($daysLeft / 30) }} month(s) left
                                            @elseif($daysLeft > 0)
                                                {{ $daysLeft }} day(s) left
                                            @elseif($daysLeft === 0)
                                                <span class="text-warning">Expires today</span>
                                            @else
                                                <span class="text-danger">Expired {{ abs($daysLeft) }} day(s) ago</span>
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">No expiry date</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.residents.id.show', $resident->id) }}">
                                        <i class="fas fa-id-card mr-1"></i>Manage ID
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <span style="display:inline-block;width:120px;height:120px;border-radius:50%;background:#f3f4f6;border:4px solid #e5e7eb;display:flex;align-items:center;justify-content:center;">
                            <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="28" cy="28" r="28" fill="#e5e7eb"/>
                                <ellipse cx="28" cy="24" rx="10" ry="12" fill="#f3f4f6"/>
                                <circle cx="23" cy="22" r="2" fill="#bdbdbd"/>
                                <circle cx="33" cy="22" r="2" fill="#bdbdbd"/>
                                <rect x="26" y="28" width="4" height="2" rx="1" fill="#bdbdbd"/>
                            </svg>
                        </span>
                    </div>
                    <h4>No residents expiring soon</h4>
                   
            @endif
        </div>
    </div>
</div>

<style>
.avatar {
    position: relative;
    width: 40px;
    height: 40px;
}
.avatar-sm {
    width: 32px;
    height: 32px;
}
.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 1px solid #dee2e6;
}
.avatar-letter {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: white;
    font-size: 14px;
    font-weight: bold;
}

/* Responsive adjustments for smaller screens */
@media (max-width: 768px) {
    .avatar {
        width: 28px;
        height: 28px;
    }
    .avatar-sm {
        width: 24px;
        height: 24px;
    }
    .avatar-letter {
        font-size: 11px;
    }
    /* Adjust table layout for mobile */
    .table-responsive table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.875rem;
    }
    .table-responsive table td .d-flex {
        flex-direction: row;
        align-items: center;
    }
    .table-responsive table td .avatar {
        margin-right: 0.5rem;
        flex-shrink: 0;
    }
}

@media (max-width: 576px) {
    .avatar {
        width: 24px;
        height: 24px;
    }
    .avatar-sm {
        width: 20px;
        height: 20px;
    }
    .avatar-letter {
        font-size: 10px;
    }
    .table-responsive table td {
        padding: 0.375rem 0.25rem;
        font-size: 0.8rem;
    }
}
</style>