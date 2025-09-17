@php
// This partial expects $pendingIssuance to be passed in
@endphp
<div class="card shadow-lg border-0 admin-card-shadow mb-4">
    <div class="card-header" style="background: #3498db; color: white; border-radius: 12px 12px 0 0 !important; border: none;">
        <strong class="card-title">Pending ID Issuance</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($pendingIssuance->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['issuance_sort' => 'barangay_id', 'issuance_direction' => request('issuance_sort') == 'barangay_id' && request('issuance_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'issuance']) }}" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                    Barangay ID
                                    @if(request('issuance_sort') == 'barangay_id')
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
                            <th>Type</th>
                            <th>Age/Gender</th>
                            <th>Requested At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingIssuance as $resident)
                            @php
                                $isLast = $loop->last;
                            @endphp
                            <tr>
                                <td><strong>{{ $resident->barangay_id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if($resident->photo)
                                                <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                            @else
                                                <div class="avatar-letter rounded-circle bg-warning">{{ substr($resident->first_name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                            @if($resident->middle_name)
                                                {{ substr($resident->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $resident->suffix }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $resident->type_of_resident }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
                                    <br><small class="text-muted">{{ $resident->sex }}</small>
                                </td>
                                <td>{{ $resident->created_at ? $resident->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.residents.id.show', $resident->id) }}">
                                                <i class="fas fa-id-card mr-2"></i>Manage ID
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to issue an ID for this resident?')){ document.getElementById('issue-{{ $resident->id }}').submit(); }">
                                                <i class="fas fa-check-circle text-success mr-2"></i>Issue ID
                                            </a>
                                            <form id="issue-{{ $resident->id }}" action="{{ route('admin.residents.id.issue', $resident->id) }}" method="POST" style="display:none;">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="{{ route('admin.residents.id.preview', $resident->id) }}">
                                                <i class="fas fa-image text-info mr-2"></i>Preview ID
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.residents.id.download', $resident->id) }}">
                                                <i class="fas fa-download text-success mr-2"></i>Download ID
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Remove this resident from the issuance queue?')){ document.getElementById('remove-issuance-{{ $resident->id }}').submit(); }">
                                                <i class="fas fa-minus-circle text-warning mr-2"></i>Remove from Issuance Queue
                                            </a>
                                            <form id="remove-issuance-{{ $resident->id }}" action="{{ route('admin.residents.id.remove-issuance', $resident->id) }}" method="POST" style="display:none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $pendingIssuance->firstItem() ?? 0 }} to {{ $pendingIssuance->lastItem() ?? 0 }} of {{ $pendingIssuance->total() }} pending issuance
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            @if($pendingIssuance->onFirstPage())
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $pendingIssuance->previousPageUrl() }}&tab=issuance"><i class="fe fe-arrow-left"></i> Previous</a></li>
                            @endif
                            @for($i = 1; $i <= $pendingIssuance->lastPage(); $i++)
                                <li class="page-item {{ $i == $pendingIssuance->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $pendingIssuance->url($i) }}&tab=issuance">{{ $i }}</a>
                                </li>
                            @endfor
                            @if($pendingIssuance->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $pendingIssuance->nextPageUrl() }}&tab=issuance">Next <i class="fe fe-arrow-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
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
                    <h4>No residents pending ID issuance</h4>
                    <p class="text-muted">No residents match your search or filter criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>