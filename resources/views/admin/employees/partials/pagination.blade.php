@if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3"
        style="border-color: #e5e7eb;">
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
                    <a class="page-link border-0 ajax-page-link" href="javascript:void(0)"
                        data-page="{{ $paginator->currentPage() - 1 }}" style="border-radius: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    </a>
                </li>

                @php
                    $current = $paginator->currentPage();
                    $last = $paginator->lastPage();
                    $maxVisible = 4;
                    $half = floor($maxVisible / 2);
                    $start = max(1, $current - $half);
                    $end = min($last, $current + $half);
                    if ($start > 1 && $end - $start < $maxVisible - 1) {
                        $start = max(1, $end - $maxVisible + 1);
                    }
                    if ($end < $last && $end - $start < $maxVisible - 1) {
                        $end = min($last, $start + $maxVisible - 1);
                    }
                @endphp

                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                        @if ($i == $current)
                            <span class="page-link border-0"
                                style="border-radius: 6px; background: #f8773c; color: white; font-weight: 600;">{{ $i }}</span>
                        @else
                            <a class="page-link border-0 ajax-page-link" href="javascript:void(0)"
                                data-page="{{ $i }}"
                                style="border-radius: 6px; color: #6b7280;">{{ $i }}</a>
                        @endif
                    </li>
                @endfor

                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link border-0 ajax-page-link" href="javascript:void(0)"
                        data-page="{{ $paginator->currentPage() + 1 }}" style="border-radius: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@elseif($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-center py-4" style="border-color: #e5e7eb;">
        <p class="text-muted small mb-0">Showing all {{ $paginator->total() }} results</p>
    </div>
@endif
