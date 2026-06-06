@if($paginator->hasPages())
    <div class="flex justify-center mt-12">
        <nav class="flex items-center gap-2" aria-label="Pagination">
            @if($paginator->onFirstPage())
                <span
                    class="w-10 h-10 rounded-full bg-surface-container-high text-outline-variant flex items-center justify-center cursor-not-allowed">
                    <span class="material-symbols-outlined text-base">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="w-10 h-10 rounded-full bg-surface-container-lowest text-on-surface-variant flex items-center justify-center hover:bg-primary-fixed hover:text-on-primary-fixed transition-all duration-300">
                    <span class="material-symbols-outlined text-base">chevron_left</span>
                </a>
            @endif

            <div class="flex gap-2">
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                    
                    if ($start > 1) {
                        $firstPageUrl = $paginator->url(1);
                        echo '<a href="' . $firstPageUrl . '" class="w-10 h-10 rounded-full bg-surface-container-lowest text-on-surface-variant flex items-center justify-center text-sm font-medium hover:bg-primary-fixed hover:text-on-primary-fixed transition-all duration-300">1</a>';
                        if ($start > 2) {
                            echo '<span class="w-10 h-10 rounded-full bg-surface-container-high text-outline-variant flex items-center justify-center text-sm font-medium">...</span>';
                        }
                    }
                    
                    for ($page = $start; $page <= $end; $page++) {
                        $url = $paginator->url($page);
                        if ($page == $currentPage) {
                            echo '<span class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-primary/20">' . $page . '</span>';
                        } else {
                            echo '<a href="' . $url . '" class="w-10 h-10 rounded-full bg-surface-container-lowest text-on-surface-variant flex items-center justify-center text-sm font-medium hover:bg-primary-fixed hover:text-on-primary-fixed transition-all duration-300">' . $page . '</a>';
                        }
                    }
                    
                    if ($end < $lastPage) {
                        if ($end < $lastPage - 1) {
                            echo '<span class="w-10 h-10 rounded-full bg-surface-container-high text-outline-variant flex items-center justify-center text-sm font-medium">...</span>';
                        }
                        $lastPageUrl = $paginator->url($lastPage);
                        echo '<a href="' . $lastPageUrl . '" class="w-10 h-10 rounded-full bg-surface-container-lowest text-on-surface-variant flex items-center justify-center text-sm font-medium hover:bg-primary-fixed hover:text-on-primary-fixed transition-all duration-300">' . $lastPage . '</a>';
                    }
                @endphp
            </div>

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="w-10 h-10 rounded-full bg-surface-container-lowest text-on-surface-variant flex items-center justify-center hover:bg-primary-fixed hover:text-on-primary-fixed transition-all duration-300">
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                </a>
            @else
                <span
                    class="w-10 h-10 rounded-full bg-surface-container-high text-outline-variant flex items-center justify-center cursor-not-allowed">
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                </span>
            @endif
        </nav>
    </div>
@endif