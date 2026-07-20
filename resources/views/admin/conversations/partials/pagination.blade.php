@if ($paginator->hasPages())
    <nav class="conv-pagination" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="conv-page-btn conv-page-disabled"><i class="ti ti-chevron-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="conv-page-btn" rel="prev"><i class="ti ti-chevron-left"></i></a>
        @endif

        <div class="conv-page-numbers">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="conv-page-ellipsis">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="conv-page-btn conv-page-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="conv-page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="conv-page-btn" rel="next"><i class="ti ti-chevron-right"></i></a>
        @else
            <span class="conv-page-btn conv-page-disabled"><i class="ti ti-chevron-right"></i></span>
        @endif
    </nav>
@endif

@once
@push('styles')
<style>
.conv-pagination {
    display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 32px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.conv-page-numbers { display: flex; align-items: center; gap: 4px; }
.conv-page-btn {
    display: flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px;
    background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.06); border-radius: 8px;
    font-size: 13px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.2s ease; cursor: pointer;
}
.conv-page-btn:hover:not(.conv-page-disabled):not(.conv-page-active) {
    background: #f8fafc; border-color: #cbd5e1; color: #0f172a;
}
.conv-page-btn i { font-size: 16px; stroke-width: 2; }
.conv-page-active {
    background: #f8773c; border-color: #f8773c; color: #ffffff; box-shadow: 0 2px 8px rgba(248, 119, 60, 0.25);
}
.conv-page-disabled { background: #f8fafc; border-color: transparent; color: #cbd5e1; cursor: not-allowed; }
.conv-page-ellipsis { display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; font-size: 14px; font-weight: 600; color: #94a3b8; }
</style>
@endpush
@endonce