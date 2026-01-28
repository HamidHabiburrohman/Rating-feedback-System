{{-- resources/views/admin/unit-types/partials/pagination.blade.php --}}
@if($unitTypes instanceof \Illuminate\Pagination\LengthAwarePaginator && $unitTypes->hasPages())
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3" style="border-color: #e5e7eb;">
    <div class="mb-3 mb-md-0">
        <p class="mb-0 text-muted small">
            Showing {{ ($unitTypes->currentPage() - 1) * $unitTypes->perPage() + 1 }} to 
            {{ min($unitTypes->currentPage() * $unitTypes->perPage(), $unitTypes->total()) }} of 
            {{ $unitTypes->total() }} results
        </p>
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination mb-0" style="gap: 2px;">
            {{-- Previous --}}
            <li class="page-item {{ $unitTypes->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link border-0" href="{{ $unitTypes->previousPageUrl() }}" style="border-radius: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </a>
            </li>

            {{-- Smart Pages (Max 4 buttons) --}}
            @php
                $current = $unitTypes->currentPage();
                $last = $unitTypes->lastPage();
                
                // Tentukan range halaman yang ditampilkan
                $maxVisible = 4;
                $half = floor($maxVisible / 2);
                
                $start = $current - $half;
                $end = $current + $half;
                
                // Adjust jika melebihi batas
                if ($start < 1) {
                    $start = 1;
                    $end = min($maxVisible, $last);
                }
                
                if ($end > $last) {
                    $end = $last;
                    $start = max(1, $last - $maxVisible + 1);
                }
                
                // Pastikan hanya 4 button
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
                        <a class="page-link border-0" href="{{ $unitTypes->url($i) }}" style="border-radius: 6px;">{{ $i }}</a>
                    @endif
                </li>
            @endfor

            {{-- Next --}}
            <li class="page-item {{ !$unitTypes->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link border-0" href="{{ $unitTypes->nextPageUrl() }}" style="border-radius: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</div>

<style>
.pagination {
    --bs-pagination-color: #6b7280;
    --bs-pagination-bg: transparent;
    --bs-pagination-border-color: #e5e7eb;
    --bs-pagination-hover-color: #374151;
    --bs-pagination-hover-bg: #f9fafb;
    --bs-pagination-hover-border-color: #d1d5db;
    --bs-pagination-focus-color: #374151;
    --bs-pagination-focus-bg: #f9fafb;
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.1);
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: #3b82f6;
    --bs-pagination-active-border-color: #3b82f6;
    --bs-pagination-disabled-color: #9ca3af;
    --bs-pagination-disabled-bg: transparent;
    --bs-pagination-disabled-border-color: #e5e7eb;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0 8px;
    margin: 0;
    border: 1px solid var(--bs-pagination-border-color);
    transition: all 0.2s ease;
}

.page-link:hover:not(.disabled):not(.active) {
    background-color: var(--bs-pagination-hover-bg);
    border-color: var(--bs-pagination-hover-border-color);
    transform: translateY(-1px);
}

.page-item.active .page-link {
    background-color: var(--bs-pagination-active-bg);
    border-color: var(--bs-pagination-active-border-color);
    box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
}

.page-item.disabled .page-link {
    color: var(--bs-pagination-disabled-color);
    background-color: var(--bs-pagination-disabled-bg);
    border-color: var(--bs-pagination-disabled-border-color);
    pointer-events: none;
}

/* Ikon panah */
.page-link svg {
    stroke-width: 2.5;
}

.page-item:first-child .page-link {
    margin-right: 4px;
}

.page-item:last-child .page-link {
    margin-left: 4px;
}

/* Responsive */
@media (max-width: 576px) {
    .page-link {
        min-width: 32px;
        height: 32px;
        font-size: 0.8125rem;
    }
    
    .d-flex.flex-column.flex-md-row {
        text-align: center;
    }
    
    .mb-3.mb-md-0 {
        margin-bottom: 1rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll ke atas saat pindah halaman
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && 
                !this.parentElement.classList.contains('active')) {
                // Smooth scroll ke atas
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@elseif($unitTypes instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="d-flex justify-content-center py-4 border-top" style="border-color: #e5e7eb;">
    <p class="text-muted small mb-0">Showing all {{ $unitTypes->total() }} results</p>
</div>
@endif