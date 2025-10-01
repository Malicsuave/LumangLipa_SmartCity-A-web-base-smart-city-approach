@php
// This partial expects $seniorCitizens to be passed in
@endphp
<div class="card shadow-lg border-0 admin-card-shadow mb-4">
    <div class="card-header" style="background: #3498db; color: white; border-radius: 12px 12px 0 0 !important; border: none;">
        <strong class="card-title">Pending Senior Citizen ID Issuance</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($seniorCitizens->count() > 0)
                <table class="table table-bordered table-striped" id="issuanceTable" data-export-title="Pending Senior Citizen ID Issuance">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['issuance_sort' => 'senior_id_number', 'issuance_direction' => request('issuance_sort') == 'senior_id_number' && request('issuance_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'issuance']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                    Senior ID
                                    @if(request('issuance_sort') == 'senior_id_number')
                                        @if(request('issuance_direction') == 'asc')
                                            <i class="fe fe-chevron-up ml-1"></i>
                                        @else
                                            <i class="fe fe-chevron-down ml-1"></i>
                                        @endif
                                    @else
                                        <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['issuance_sort' => 'name', 'issuance_direction' => request('issuance_sort') == 'name' && request('issuance_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'issuance']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                    Name
                                    @if(request('issuance_sort') == 'name')
                                        @if(request('issuance_direction') == 'asc')
                                            <i class="fe fe-chevron-up ml-1"></i>
                                        @else
                                            <i class="fe fe-chevron-down ml-1"></i>
                                        @endif
                                    @else
                                        <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Age/Gender</th>
                            <th>Date Registered</th>
                            <th>Emergency Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seniorCitizens as $senior)
                            @php
                                $isLast = $loop->last;
                            @endphp
                            <tr>
                                <td><strong>{{ $senior->senior_id_number }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($senior->photo)
                                                <img src="{{ asset('storage/residents/photos/' . $senior->photo) }}" 
                                                     alt="{{ $senior->full_name }}" 
                                                     class="avatar-img rounded-circle"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                     loading="lazy">
                                                <div class="avatar-letter rounded-circle bg-secondary" style="display: none;">
                                                    {{ substr($senior->first_name, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="avatar-letter rounded-circle" style="background-color: {{ $senior->sex === 'Female' ? '#e91e63' : '#2196f3' }};">
                                                    {{ substr($senior->first_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $senior->last_name }}, {{ $senior->first_name }}</strong>
                                            @if($senior->middle_name)
                                                {{ substr($senior->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $senior->suffix }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($senior->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $senior->sex }}</small>
                                </td>
                                <td>{{ $senior->date_registered ? \Carbon\Carbon::parse($senior->date_registered)->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($senior->emergency_contact_name)
                                        <strong>{{ $senior->emergency_contact_name }}</strong>
                                        @if($senior->emergency_contact_number)
                                            <br><small class="text-muted">{{ $senior->emergency_contact_number }}</small>
                                        @endif
                                        @if($senior->emergency_contact_relationship)
                                            <br><small class="text-muted">({{ $senior->emergency_contact_relationship }})</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id-management', $senior) }}">
                                                <i class="fas fa-id-card mr-2"></i>Manage ID
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to issue a Senior Citizen ID for this person?')){ document.getElementById('issue-{{ $senior->id }}').submit(); }">
                                                <i class="fas fa-check-circle mr-2"></i>Issue ID
                                            </a>
                                            <form id="issue-{{ $senior->id }}" action="{{ route('admin.senior-citizens.issue-id', $senior) }}" method="POST" style="display:none;">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id.preview', $senior) }}">
                                                <i class="fas fa-image mr-2"></i>Preview ID
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.senior-citizens.id.download', $senior) }}">
                                                <i class="fas fa-download mr-2"></i>Download ID
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Remove this senior citizen from the issuance queue?')){ document.getElementById('remove-issuance-{{ $senior->id }}').submit(); }">
                                                <i class="fas fa-minus-circle mr-2"></i>Remove from Issuance Queue
                                            </a>
                                            <form id="remove-issuance-{{ $senior->id }}" action="{{ route('admin.senior-citizens.remove-issuance', $senior) }}" method="POST" style="display:none;">
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
                <div class="text-center py-5" id="pendingIssuanceNoResults">
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
                    <h4>No senior citizens pending ID issuance</h4>
                    <p class="text-muted">All senior citizens have their IDs issued or there are no registered senior citizens yet.</p>
                    <a href="{{ route('admin.senior-citizens.register') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>Register Senior Citizen
                    </a>
                </div>
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
