@props([
    'paginator',
    'showInfo' => true,
    'showPrevNext' => true,
    'class' => '',
])

{{-- DEBUG SUPER DETAIL --}}
@php
    error_log('========== PAGINATION COMPONENT DEBUG ==========');
    error_log('1. Raw paginator value: ' . print_r($paginator, true));
    error_log('2. Paginator type: ' . gettype($paginator));
    
    if (is_string($paginator)) {
        error_log('3. String length: ' . strlen($paginator));
        error_log('4. First 100 chars: ' . substr($paginator, 0, 100));
        error_log('5. Is JSON? ' . (json_decode($paginator) ? 'Yes' : 'No'));
        if (json_decode($paginator)) {
            error_log('6. JSON decode result: ' . print_r(json_decode($paginator, true), true));
        }
    }
    
    if (is_object($paginator)) {
        error_log('3. Object class: ' . get_class($paginator));
        error_log('4. Object methods: ' . print_r(get_class_methods($paginator), true));
    }
    
    error_log('=============================================');
@endphp

{{-- Tampilkan debug di browser --}}
<div style="background: #fff3cd; color: #856404; padding: 15px; margin: 10px 0; border: 1px solid #ffeeba; border-radius: 4px;">
    <strong>🔍 PAGINATION DEBUG INFO:</strong><br>
    <hr>
    <strong>Type:</strong> {{ gettype($paginator) }}<br>
    
    @if(is_string($paginator))
        <strong>String Value (first 200 chars):</strong><br>
        <div style="background: #f8f9fa; padding: 10px; margin: 5px 0; font-family: monospace; font-size: 12px; overflow: auto; max-height: 200px;">
            {{ substr($paginator, 0, 200) }}{{ strlen($paginator) > 200 ? '...' : '' }}
        </div>
        
        @if(json_decode($paginator))
            <strong>Is JSON:</strong> Yes<br>
            <strong>JSON Data:</strong>
            <pre style="background: #f8f9fa; padding: 10px; margin: 5px 0; font-family: monospace; font-size: 12px; max-height: 300px; overflow: auto;">
{{ json_encode(json_decode($paginator, true), JSON_PRETTY_PRINT) }}
            </pre>
        @else
            <strong>Is JSON:</strong> No<br>
        @endif
    @endif
    
    @if(is_object($paginator))
        <strong>Class:</strong> {{ get_class($paginator) }}<br>
        <strong>Has Pages:</strong> {{ method_exists($paginator, 'hasPages') ? ($paginator->hasPages() ? 'Yes' : 'No') : 'Method not exists' }}<br>
        <strong>Total:</strong> {{ method_exists($paginator, 'total') ? $paginator->total() : 'N/A' }}<br>
        <strong>Last Page:</strong> {{ method_exists($paginator, 'lastPage') ? $paginator->lastPage() : 'N/A' }}<br>
    @endif
</div>

@if(!$paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div style="background: #fee2e2; color: #991b1b; padding: 20px; margin: 10px 0; border: 2px solid #dc2626; border-radius: 4px;">
        <strong style="font-size: 16px;">❌ ERROR: Invalid paginator data</strong><br>
        <hr>
        <strong>Expected:</strong> Illuminate\Pagination\LengthAwarePaginator<br>
        <strong>Received:</strong> {{ is_object($paginator) ? get_class($paginator) : gettype($paginator) }}<br>
        
        @if(is_string($paginator))
            <hr>
            <strong>String content:</strong><br>
            <div style="background: #fff; padding: 10px; margin-top: 5px; font-family: monospace;">
                "{{ $paginator }}"
            </div>
        @endif
    </div>
@else
    {{-- Tampilkan pagination normal --}}
    @if($paginator->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3 {{ $class }}" style="border-color: #e5e7eb;">
            
            {{-- Info showing results --}}
            @if($showInfo)
                <div class="mb-3 mb-md-0">
                    <p class="mb-0 text-muted small">
                        Showing {{ $paginator->firstItem() }} to 
                        {{ $paginator->lastItem() }} of 
                        {{ $paginator->total() }} results
                    </p>
                </div>
            @endif

            {{-- Pagination links --}}
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0" style="gap: 2px;">
                    
                    {{-- Previous button --}}
                    @if($showPrevNext)
                        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link border-0" 
                               href="{{ $paginator->previousPageUrl() }}" 
                               style="border-radius: 6px;"
                               {{ $paginator->onFirstPage() ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Page numbers --}}
                    @for($i = 1; $i <= $paginator->lastPage(); $i++)
                        @if(
                            $i == 1 || 
                            $i == $paginator->lastPage() || 
                            ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                        )
                            <li class="page-item {{ $i == $paginator->currentPage() ? 'active' : '' }}">
                                @if($i == $paginator->currentPage())
                                    <span class="page-link border-0" style="border-radius: 6px;">{{ $i }}</span>
                                @else
                                    <a class="page-link border-0" 
                                       href="{{ $paginator->url($i) }}" 
                                       style="border-radius: 6px;">{{ $i }}</a>
                                @endif
                            </li>
                        @elseif(
                            ($i == 2 && $paginator->currentPage() > 4) ||
                            ($i == $paginator->lastPage() - 1 && $paginator->currentPage() < $paginator->lastPage() - 3)
                        )
                            <li class="page-item disabled">
                                <span class="page-link border-0" style="border-radius: 6px;">...</span>
                            </li>
                        @endif
                    @endfor

                    {{-- Next button --}}
                    @if($showPrevNext)
                        <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link border-0" 
                               href="{{ $paginator->nextPageUrl() }}" 
                               style="border-radius: 6px;"
                               {{ !$paginator->hasMorePages() ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @else
        {{-- Tampilkan jika hanya 1 halaman --}}
        <div class="d-flex justify-content-center py-4" style="border-color: #e5e7eb;">
            <p class="text-muted small mb-0">Showing all {{ $paginator->total() }} results</p>
        </div>
    @endif
@endif

@push('scripts')
<script src="{{ asset('assets/components/js/pagination.js') }}"></script>
@endpush