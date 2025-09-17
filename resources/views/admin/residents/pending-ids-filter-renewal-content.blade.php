{{-- Filters removed as per request. Leaving empty content. --}}

{{-- Table for Pending Renewal --}}
@if($pendingRenewal->count() > 0)
    <div class="table-responsive">
        <table class="table table-borderless table-striped" id="pendingRenewalTable">
            @include('admin.residents.partials.pending-ids-table-renewal', ['pendingRenewal' => $pendingRenewal])
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Showing {{ $pendingRenewal->firstItem() ?? 0 }} to {{ $pendingRenewal->lastItem() ?? 0 }} of {{ $pendingRenewal->total() }} pending renewals
        </div>
        <nav aria-label="Table Paging" class="mb-0">
            <ul class="pagination justify-content-end mb-0">
                @if($pendingRenewal->onFirstPage())
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $pendingRenewal->previousPageUrl() }}&tab=renewal"><i class="fe fe-arrow-left"></i> Previous</a></li>
                @endif
                @for($i = 1; $i <= $pendingRenewal->lastPage(); $i++)
                    <li class="page-item {{ $i == $pendingRenewal->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $pendingRenewal->url($i) }}&tab=renewal">{{ $i }}</a>
                    </li>
                @endfor
                @if($pendingRenewal->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $pendingRenewal->nextPageUrl() }}&tab=renewal">Next <i class="fe fe-arrow-right"></i></a></li>
                @else
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                @endif
            </ul>
        </nav>
    </div>
@else
    <div class="text-center py-5" id="pendingRenewalNoResults">
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
        <h4>No pending renewals</h4>
        <p class="text-muted">No resident IDs are currently marked for renewal.</p>
    </div>
@endif 