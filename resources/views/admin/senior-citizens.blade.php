<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
        Showing {{ $seniorCitizens->firstItem() ?? 0 }} to {{ $seniorCitizens->lastItem() ?? 0 }} of {{ $seniorCitizens->total() }} senior citizens
    </div>
    <nav aria-label="Table Paging" class="mb-0">
        <ul class="pagination justify-content-end mb-0">
            {!! $seniorCitizens->appends(request()->query())->links() !!}
        </ul>
    </nav>
</div> 