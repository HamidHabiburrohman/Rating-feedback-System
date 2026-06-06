@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->hasPages())
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3" style="border-color: #e5e7eb;">
    <div class="mb-3 mb-md-0">
        <p class="mb-0 text-muted small">
            Showing {{ ($paginator->currentPage() - 1) * $paginator->perPage() + 1 }} to 
            {{ min($paginator->currentPage() * $paginator->perPage(), $paginator->total()) }} of 
            {{ $paginator->total() }} results
        </p>
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination mb-0" style="gap: 2px;">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link border-0" href="{{ $paginator->previousPageUrl() }}" style="border-radius: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </a>
            </li>

            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                
                $maxVisible = 4;
                $half = floor($maxVisible / 2);
                
                $start = $current - $half;
                $end = $current + $half;
                
                if ($start < 1) {
                    $start = 1;
                    $end = min($maxVisible, $last);
                }
                
                if ($end > $last) {
                    $end = $last;
                    $start = max(1, $last - $maxVisible + 1);
                }
                
                $visiblePages = $end - $start + 1;
                if ($visiblePages < $maxVisible && $start > 1) {
                    $start = max(1, $end - $maxVisible + 1);
                }
            @endphp

            @for($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    @if($i == $current)
                        <span class="page-link border-0" style="border-radius: 6px;">{{ $i }}</span>
                    @else
                        <a class="page-link border-0" href="{{ $paginator->url($i) }}" style="border-radius: 6px;">{{ $i }}</a>
                    @endif
                </li>
            @endfor

            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link border-0" href="{{ $paginator->nextPageUrl() }}" style="border-radius: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && 
                !this.parentElement.classList.contains('active')) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@elseif($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="d-flex justify-content-center py-4" style="border-color: #e5e7eb;">
    <p class="text-muted small mb-0">Showing all {{ $paginator->total() }} results</p>
</div>
@endif