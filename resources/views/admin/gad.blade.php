<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
        Showing {{ $gadRequests->firstItem() ?? 0 }} to {{ $gadRequests->lastItem() ?? 0 }} of {{ $gadRequests->total() }} GAD requests
    </div>
    <nav aria-label="Table Paging" class="mb-0">
        <ul class="pagination justify-content-end mb-0">
            {!! $gadRequests->appends(request()->query())->links() !!}
        </ul>
    </nav>
</div> 