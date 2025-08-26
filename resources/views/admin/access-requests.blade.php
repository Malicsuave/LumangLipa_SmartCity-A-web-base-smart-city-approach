<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
        Showing {{ $accessRequests->firstItem() ?? 0 }} to {{ $accessRequests->lastItem() ?? 0 }} of {{ $accessRequests->total() }} access requests
    </div>
    <nav aria-label="Table Paging" class="mb-0">
        <ul class="pagination justify-content-end mb-0">
            {!! $accessRequests->appends(request()->query())->links() !!}
        </ul>
    </nav>
</div> 