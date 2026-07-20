@props(['file', 'index' => 0, 'removable' => true])

@php
    $isImage = isset($file->mime_type)
        ? str_starts_with($file->mime_type, 'image/')
        : (isset($file['type']) ? str_starts_with($file['type'], 'image/') : false);

    $name = $file->original_name ?? ($file['name'] ?? 'Unknown');
    $size = $file->size ?? ($file['size'] ?? 0);
    $ext = pathinfo($name, PATHINFO_EXTENSION);

    $icons = [
        'pdf' => 'ti-file-text',
        'doc' => 'ti-file-word', 'docx' => 'ti-file-word',
        'xls' => 'ti-file-excel', 'xlsx' => 'ti-file-excel',
        'png' => 'ti-photo', 'jpg' => 'ti-photo',
        'jpeg' => 'ti-photo', 'webp' => 'ti-photo'
    ];
    $icon = $icons[strtolower($ext)] ?? 'ti-file';

    $formattedSize = '';
    if ($size < 1024) $formattedSize = $size . ' B';
    elseif ($size < 1048576) $formattedSize = round($size / 1024, 1) . ' KB';
    else $formattedSize = round($size / 1048576, 1) . ' MB';
@endphp

<div class="conv-att-preview-card">
    @if($isImage)
        <div class="conv-att-thumb">
            <img src="{{ isset($file->path) ? asset('storage/' . $file->path) : ($file['url'] ?? '') }}"
                 alt="{{ $name }}">
        </div>
    @else
        <div class="conv-att-preview-icon">
            <i class="ti {{ $icon }}"></i>
        </div>
    @endif

    <div class="conv-att-preview-info">
        <p class="conv-att-preview-name" title="{{ $name }}">{{ $name }}</p>
        <span class="conv-att-preview-meta">{{ strtoupper($ext) }} • {{ $formattedSize }}</span>
    </div>

    @if($removable)
        <button type="button"
                class="conv-att-preview-remove"
                data-index="{{ $index }}"
                title="Remove">
            <i class="ti ti-x"></i>
        </button>
    @endif
</div>