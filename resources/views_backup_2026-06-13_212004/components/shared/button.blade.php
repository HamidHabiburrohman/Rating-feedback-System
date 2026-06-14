@php
    $icons = [
        'show' => '<svg width="18" height="18" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon btn-icon-show">
                        <path d="M3.22683 16.9513C3.04526 16.6637 2.95446 16.5199 2.90363 16.2982C2.86546 16.1317 2.86546 15.869 2.90363 15.7025C2.95446 15.4807 3.04526 15.337 3.22683 15.0494C4.72738 12.6734 9.19387 6.66699 16.0005 6.66699C22.8072 6.66699 27.2737 12.6734 28.7743 15.0494C28.9559 15.337 29.0467 15.4807 29.0975 15.7025C29.1356 15.869 29.1356 16.1317 29.0975 16.2982C29.0467 16.5199 28.9559 16.6637 28.7743 16.9513C27.2737 19.3273 22.8072 25.3337 16.0005 25.3337C9.19387 25.3337 4.72738 19.3273 3.22683 16.9513Z" stroke="#4A4947" stroke-width="2"/>
                        <path d="M16 20C18.2091 20 20 18.2091 20 16C20 13.7909 18.2091 12 16 12C13.7909 12 12 13.7909 12 16C12 18.2091 13.7909 20 16 20Z" stroke="#4A4947" stroke-width="2"/>
                    </svg>',

        'edit' => '<svg width="18" height="18" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon btn-icon-edit">
                        <path d="M21.6189 6.64318L23.4878 4.77417C24.5201 3.74194 26.1937 3.74194 27.2259 4.77417C28.2581 5.8064 28.2581 7.47997 27.2259 8.5122L25.3569 10.3812M21.6189 6.64318L14.6403 13.6217C13.2467 15.0155 12.5498 15.7123 12.0752 16.5615C11.6007 17.4107 11.1233 19.4159 10.6667 21.3333C12.5842 20.8768 14.5894 20.3993 15.4386 19.9248C16.2878 19.4503 16.9846 18.7535 18.3783 17.3597L25.3569 10.3812M21.6189 6.64318L25.3569 10.3812" stroke="#4A4947" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M28 16C28 21.6568 28 24.4853 26.2427 26.2427C24.4853 28 21.6568 28 16 28C10.3431 28 7.51472 28 5.75736 26.2427C4 24.4853 4 21.6568 4 16C4 10.3431 4 7.51472 5.75736 5.75736C7.51472 4 10.3431 4 16 4" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                    </svg>',

        'delete' => '<svg width="18" height="18" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon btn-icon-delete">
                        <path d="M25.3334 9.33301L24.177 25.523C24.0773 26.9185 22.9161 27.9997 21.5171 27.9997H10.4831C9.08403 27.9997 7.92285 26.9185 7.82317 25.523L6.66675 9.33301" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                        <path d="M13.3333 14.667V22.667" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18.6667 14.667V22.667" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 9.33301H28" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 9.33333V5.33333C12 4.59696 12.597 4 13.3333 4H18.6667C19.4031 4 20 4.59696 20 5.33333V9.33333" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                    </svg>',

        'reply' => '<svg width="18" height="18" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon btn-icon-reply">
                        <path d="M9.33325 11.9997C9.33325 8.31778 12.318 5.33301 15.9999 5.33301C19.6818 5.33301 22.6666 8.31778 22.6666 11.9997V14.6663C22.6666 18.3482 19.6818 21.333 15.9999 21.333H5.33325" stroke="#4A4947" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9.33325 17.333L5.33325 21.333L9.33325 25.333" stroke="#4A4947" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>',
    ];

    // Menambahkan icon untuk reply-full dengan ukuran yang sama
    $icons['reply-full'] = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>';

    // Default untuk icon button (bulat)
    $buttonSize = 'width: 34px; height: 34px;';
    $buttonClass = 'btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn admin-action-btn';

    // Untuk reply-full button (dengan teks)
    $fullButtonClass = 'btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3 border-0';
    $fullButtonStyle = 'background: linear-gradient(135deg, #f8773c, #e55a2b); color: white; box-shadow: 0 4px 12px rgba(248,119,60,0.15);';

    $iconSvg = $icons[$type] ?? $icons['show'];

    $isDisabled = $disabled ?? false;
    $disabledStyle = $isDisabled ? 'opacity: 0.4; cursor: not-allowed;' : '';
    $disabledTooltip = $isDisabled ? ($disabledTooltip ?? 'Button is disabled') : null;

    $isLink = !$isDisabled && isset($url) && $url && !isset($modalId) && !isset($onclick);
    $isModal = !$isDisabled && isset($modalId) && $modalId && !isset($url) && !isset($onclick);
    $isClick = !$isDisabled && isset($onclick) && $onclick && !isset($url) && !isset($modalId);
    $isDisabledButton = $isDisabled;

    // Cek apakah tipe button adalah reply-full (dengan teks)
    $isReplyFull = ($type === 'reply-full');

    $finalTooltip = $isDisabled ? ($disabledTooltip ?? $tooltip) : $tooltip;
@endphp

<style>
    .admin-action-btn {
        background-color: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.09) !important;
        transition: all 0.2s ease-in-out !important;
        padding: 8px !important;
    }

    .admin-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .admin-action-btn:hover .btn-icon-show path,
    .admin-action-btn:hover .btn-icon-show circle {
        stroke: #1E40AF !important;
    }

    .admin-action-btn:hover .btn-icon-edit path,
    .admin-action-btn:hover .btn-icon-edit circle {
        stroke: #E6AB08 !important;
    }

    .admin-action-btn:hover .btn-icon-delete path,
    .admin-action-btn:hover .btn-icon-delete line,
    .admin-action-btn:hover .btn-icon-delete polyline {
        stroke: #E20909 !important;
    }

    .admin-action-btn:hover .btn-icon-reply path {
        stroke: #15803D !important;
    }

    .admin-action-btn:disabled {
        pointer-events: none;
    }

    .admin-action-btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .admin-action-btn:disabled:hover .btn-icon-show path,
    .admin-action-btn:disabled:hover .btn-icon-show circle,
    .admin-action-btn:disabled:hover .btn-icon-edit path,
    .admin-action-btn:disabled:hover .btn-icon-edit circle,
    .admin-action-btn:disabled:hover .btn-icon-delete path,
    .admin-action-btn:disabled:hover .btn-icon-delete line,
    .admin-action-btn:disabled:hover .btn-icon-delete polyline,
    .admin-action-btn:disabled:hover .btn-icon-reply path {
        stroke: #9CA3AF !important;
    }

    /* Style untuk reply-full button */
    .admin-reply-full-btn {
        transition: all 0.2s ease-in-out;
    }

    .admin-reply-full-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(248, 119, 60, 0.25) !important;
    }
</style>

@if($isReplyFull)
    {{-- Button Reply dengan teks full --}}
    @if($isDisabledButton)
        <button type="button" class="{{ $fullButtonClass }} admin-reply-full-btn"
            style="{{ $fullButtonStyle }} {{ $disabledStyle }}" disabled @if($finalTooltip) data-bs-toggle="tooltip"
            data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
            <span class="fw-medium">Reply</span>
        </button>
    @elseif($isModal)
        <button type="button" class="{{ $fullButtonClass }} admin-reply-full-btn" style="{{ $fullButtonStyle }}"
            data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" @if($finalTooltip) data-bs-toggle="tooltip"
            data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
            <span class="fw-medium">Reply</span>
        </button>
    @elseif($isClick)
        <button type="button" class="{{ $fullButtonClass }} admin-reply-full-btn" style="{{ $fullButtonStyle }}"
            onclick="{{ $onclick }}" @if($finalTooltip) data-bs-toggle="tooltip" data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
            <span class="fw-medium">Reply</span>
        </button>
    @elseif($isLink)
        <a href="{{ $url }}" class="{{ $fullButtonClass }} admin-reply-full-btn"
            style="{{ $fullButtonStyle }} text-decoration-none" @if($finalTooltip) data-bs-toggle="tooltip"
            data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
            <span class="fw-medium">Reply</span>
        </a>
    @endif
@else
    {{-- Original icon button --}}
    @if($isDisabledButton)
        <button type="button" class="{{ $buttonClass }}" style="{{ $buttonSize }} display: inline-flex; {{ $disabledStyle }}"
            disabled @if($finalTooltip) data-bs-toggle="tooltip" data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
        </button>
    @elseif($isLink)
        <a href="{{ $url }}" class="{{ $buttonClass }}" style="{{ $buttonSize }} display: inline-flex;" @if($finalTooltip)
        data-bs-toggle="tooltip" data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
        </a>
    @elseif($isModal)
        <button type="button" class="{{ $buttonClass }}" style="{{ $buttonSize }} display: inline-flex;" data-bs-toggle="modal"
            data-bs-target="#{{ $modalId }}" @if($finalTooltip) data-bs-toggle="tooltip" data-bs-title="{{ $finalTooltip }}"
            @endif>
            {!! $iconSvg !!}
        </button>
    @elseif($isClick)
        <button type="button" class="{{ $buttonClass }}" style="{{ $buttonSize }} display: inline-flex;"
            onclick="{{ $onclick }}" @if($finalTooltip) data-bs-toggle="tooltip" data-bs-title="{{ $finalTooltip }}" @endif>
            {!! $iconSvg !!}
        </button>
    @endif
@endif