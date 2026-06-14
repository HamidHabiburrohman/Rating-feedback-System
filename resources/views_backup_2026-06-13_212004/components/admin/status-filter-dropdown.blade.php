@props([
    'currentStatus' => [],
    'filterKey' => 'status',
    'options' => [
        '1' => 'Active',
        '0' => 'Inactive'
    ],
    'buttonText' => 'Filter'
])

@php
    $currentStatus = is_array($currentStatus) ? $currentStatus : [];
    $activeStyle = 'background: #f8773c !important; border: none !important; color: white !important; background-image: none !important;';
    $inactiveStyle = 'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280 !important; background-image: none !important;';
@endphp

<div class="dropdown-menu p-0 border-0 shadow-lg rounded-4 overflow-hidden mt-2" 
    style="min-width: 300px; background-color: #ffffff;" id="statusFilterDropdown">
    <div class="p-3">
        <div class="mb-1">
            <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                style="color: #6b7280; letter-spacing: 0.05em;">{{ $buttonText }}</label>
            <div class="d-flex flex-wrap gap-2" id="statusFilter">
                <button type="button" 
                    class="btn btn-sm rounded-pill px-3 fw-medium filter-status filter-status-btn"
                    data-value=""
                    style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                    All
                </button>
                
                @foreach($options as $value => $label)
                <button type="button" 
                    class="btn btn-sm rounded-pill px-3 fw-medium filter-status filter-status-btn"
                    data-value="{{ $value }}"
                    style="{{ in_array((string)$value, $currentStatus) ? $activeStyle : $inactiveStyle }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="p-3 border-top d-flex gap-2 bg-white">
        <button type="button" id="resetFilter"
            class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center"
            style="height: 40px; background: white; border: 1px solid #d1d5db; color: #4b5563; transition: all 0.2s;">
            Reset
        </button>
        <button type="button" id="applyFilter" 
            class="btn btn-sm rounded-pill w-100 fw-semibold"
            style="height: 40px; background: #f8773c; border: none; color: white; transition: all 0.2s;">
            Apply Filter
        </button>
    </div>
</div>