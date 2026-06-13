@php
    $text = $slot->isNotEmpty() ? strip_tags($slot->toHtml()) : ($defaultText ?? 'Add New');
    $size = $size ?? 'md';
    $iconSize = $iconSize ?? 18;

    $sizes = [
        'sm' => ['height' => '36px', 'padding' => 'px-3', 'font' => '14px'],
        'md' => ['height' => '44px', 'padding' => 'px-4', 'font' => '14px'],
        'lg' => ['height' => '52px', 'padding' => 'px-5', 'font' => '16px'],
    ];

    $currentSize = $sizes[$size];
    $btnClass = "btn rounded-pill d-inline-flex align-items-center justify-content-center gap-2 btn-create-shadow {$currentSize['padding']}";
    $btnStyle = "height: {$currentSize['height']}; background: #f8773c; border: none; font-size: {$currentSize['font']}; font-weight: 500; color: white;";

    $finalIconSize = $size === 'sm' ? 14 : ($size === 'lg' ? 20 : 16);

    $isLink = isset($url) && $url;
    $isModal = isset($modalId) && $modalId;
    $isOnclick = isset($onclick) && $onclick;
@endphp

@if($isLink)
    <a href="{{ $url }}" class="{{ $btnClass }}" style="{{ $btnStyle }}" @if($tooltip) data-bs-toggle="tooltip"
    data-bs-title="{{ $tooltip }}" @endif>
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $finalIconSize }}" height="{{ $finalIconSize }}"
            viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
            <path d="M12 5v14M5 12h14" />
        </svg>
        <span style="color: white; line-height: 1;">{{ $text }}</span>
    </a>
@elseif($isModal)
    <button type="button" class="{{ $btnClass }}" style="{{ $btnStyle }}" data-bs-toggle="modal"
        data-bs-target="#{{ $modalId }}" @if($tooltip) data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" @endif>
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $finalIconSize }}" height="{{ $finalIconSize }}"
            viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
            <path d="M12 5v14M5 12h14" />
        </svg>
        <span style="color: white; line-height: 1;">{{ $text }}</span>
    </button>
@elseif($isOnclick)
    <button type="button" class="{{ $btnClass }}" style="{{ $btnStyle }}" onclick="{{ $onclick }}" @if($tooltip)
    data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" @endif>
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $finalIconSize }}" height="{{ $finalIconSize }}"
            viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
            <path d="M12 5v14M5 12h14" />
        </svg>
        <span style="color: white; line-height: 1;">{{ $text }}</span>
    </button>
@endif

<style>
    .btn-create-shadow {
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px -3px rgba(248, 119, 60, 0.3);
    }

    .btn-create-shadow:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(248, 119, 60, 0.4);
    }

    .btn-create-shadow:active {
        transform: scale(0.98);
    }
</style>