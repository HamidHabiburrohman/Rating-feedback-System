@props(['options' => [10, 25, 50, 100], 'currentPerPage' => null])

@php
    $currentPerPage = $currentPerPage ?? request('per_page', 10);
@endphp

<div class="dropdown">
    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
        type="button" data-bs-toggle="dropdown"
        style="height: 44px; background-color: white; border-color: #d1d5db;">
        <span class="fw-medium">{{ $currentPerPage }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
    <ul class="dropdown-menu border-0 shadow-lg rounded-3">
        @foreach ($options as $size)
            <li>
                <a class="dropdown-item py-2 px-3 {{ $currentPerPage == $size ? 'active bg-light text-primary fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}">
                    {{ $size }} Rows
                </a>
            </li>
        @endforeach
    </ul>
</div>