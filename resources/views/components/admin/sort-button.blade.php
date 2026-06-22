@props([
    'sortOptions' => [],
    'defaultSort' => 'name',
    'defaultOrder' => 'asc',
])

@php
    $currentSort = request('sort', $defaultSort);
    $currentOrder = request('order', $defaultOrder);
    $currentSortKey = $currentSort . '_' . $currentOrder;
    $isDefault = $currentSort === $defaultSort && $currentOrder === $defaultOrder;
    $selectedLabel = $sortOptions[$currentSortKey] ?? null;
    $buttonText = $isDefault || !$selectedLabel ? 'Sort' : 'Sort: ' . $selectedLabel;
@endphp

<div class="dropdown" id="sortDropdown">
    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
        type="button" data-bs-toggle="dropdown" style="height: 44px; background-color: white; border-color: #d1d5db;">
        <span class="fw-medium">{{ $buttonText }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
    <ul class="dropdown-menu border-0 shadow-lg mt-2">
        @foreach ($sortOptions as $key => $label)
            @php
                $lastUnderscore = strrpos($key, '_');
                $sortField = substr($key, 0, $lastUnderscore);
                $sortOrder = substr($key, $lastUnderscore + 1);
                $isActive = $currentSort == $sortField && $currentOrder == $sortOrder;
            @endphp
            <li>
                <a class="dropdown-item {{ $isActive ? 'active' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'order' => $sortOrder, 'page' => 1]) }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<style>
    #sortDropdown .dropdown-menu {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.06) !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.02) !important;
        padding: 8px !important;
        min-width: 220px;
        overflow: hidden;
    }

    #sortDropdown .dropdown-item {
        border-radius: 9999px;
        padding: 10px 16px;
        margin-bottom: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: transparent;
    }

    #sortDropdown .dropdown-item:last-child {
        margin-bottom: 0;
    }

    #sortDropdown .dropdown-item:hover:not(.active) {
        background-color: #fff5f0 !important;
        color: #f8773c !important;
    }

    #sortDropdown .dropdown-item.active {
        background-color: #f8773c !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    #sortDropdown .dropdown-item.active:hover {
        background-color: #e55a2b !important;
        color: #ffffff !important;
    }

    #sortDropdown .dropdown-toggle-btn {
        transition: all 0.2s ease;
    }

    
</style>
