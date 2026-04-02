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
                @foreach($elements as $element)
                    @if(is_string($element))
                        <span
                            class="w-10 h-10 rounded-full bg-surface-container-high text-outline-variant flex items-center justify-center text-sm font-medium">{{ $element }}</span>
                    @endif

                    @if(is_array($element))
                        @foreach($element as $page => $url)
                            @if($page == $paginator->currentPage())
                                <span
                                    class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-primary/20">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-10 h-10 rounded-full bg-surface-container-lowest text-on-surface-variant flex items-center justify-center text-sm font-medium hover:bg-primary-fixed hover:text-on-primary-fixed transition-all duration-300">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
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