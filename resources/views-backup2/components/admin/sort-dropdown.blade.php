@props(['options' => [], 'currentSort' => null, 'currentOrder' => null])

@php
    $currentSort = $currentSort ?? request('sort', 'created_at');
    $currentOrder = $currentOrder ?? request('order', 'desc');
    
    $buttonText = 'Sort';
    foreach ($options as $key => $option) {
        if (($option['sort'] ?? '') == $currentSort && ($option['order'] ?? '') == $currentOrder) {
            $buttonText = $option['label'] ?? 'Sort';
            break;
        }
    }
@endphp

<div class="dropdown" id="sortDropdown">
    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
        type="button" data-bs-toggle="dropdown"
        style="height: 44px; background-color: white; border-color: #d1d5db;">
        <span class="fw-medium">{{ $buttonText }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
    <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
        @foreach($options as $key => $option)
            <li>
                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 
                    {{ ($currentSort == ($option['sort'] ?? '') && $currentOrder == ($option['order'] ?? '')) ? 'active bg-light text-primary fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery([
                        'sort' => $option['sort'] ?? 'created_at', 
                        'order' => $option['order'] ?? 'desc', 
                        'page' => 1
                    ]) }}">
                    @if(!empty($option['icon']))
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            @foreach(explode(' ', $option['icon']) as $path)
                                <path d="{{ $path }}" />
                            @endforeach
                        </svg>
                    @endif
                    {{ $option['label'] ?? $key }}
                </a>
            </li>
        @endforeach
    </ul>
</div>