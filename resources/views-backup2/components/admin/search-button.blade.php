@props(['placeholder' => 'Search by title, description or code...'])

<div class="position-relative" style="flex: 1; max-width: 500px;">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
        stroke="#6b7280" stroke-width="2" class="position-absolute top-50 translate-middle-y ms-3">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.35-4.35" />
    </svg>
    <input type="text" 
        id="searchInput" 
        class="form-control ps-5 rounded-pill border shadow-sm"
        placeholder="{{ $placeholder }}" 
        value="{{ request('search') }}"
        style="height: 44px; background-color: white;">
</div>