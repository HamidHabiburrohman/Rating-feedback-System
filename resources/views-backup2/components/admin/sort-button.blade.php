@props([
    'sortOptions' => [],
    'defaultSort' => 'name',
    'defaultOrder' => 'asc'
])

@php
    $currentSort = request('sort', $defaultSort);
    $currentOrder = request('order', $defaultOrder);
    $currentSortKey = $currentSort . '_' . $currentOrder;
    $buttonText = $sortOptions[$currentSortKey] ?? 'Sort';
@endphp

<div class="dropdown" id="sortDropdown">
    <button
        class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
        type="button"
        data-bs-toggle="dropdown"
        style="height: 44px; background-color: white; border-color: #d1d5db;">
        <span class="fw-medium">{{ $buttonText }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
    <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
        @foreach($sortOptions as $key => $label)
            @php
                $lastUnderscore = strrpos($key, '_');
                $sortField = substr($key, 0, $lastUnderscore);
                $sortOrder = substr($key, $lastUnderscore + 1);
                $isActive = ($currentSort == $sortField && $currentOrder == $sortOrder);
            @endphp
            <li>
                <a class="dropdown-item py-2 px-3 {{ $isActive ? 'active' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'order' => $sortOrder, 'page' => 1]) }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<style>
    .dropdown-item.active {
        background-color: #f8773c !important;
        color: white !important;
    }
    
    .dropdown-item.active:hover {
        background-color: #e55a2b !important;
        color: white !important;
    }
    
    .dropdown-item:hover:not(.active) {
        background-color: #fff5f0 !important;
        color: #f8773c !important;
    }
</style>    