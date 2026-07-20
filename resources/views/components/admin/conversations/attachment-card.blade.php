{{-- resources/views/components/admin/conversations/attachment-card.blade.php --}}
@props([
    'attachment',
    'showActions' => true,
    'compact' => false,
    'removable' => false,
    'gallery' => null,
    'index' => 0,
])

@php
    $mime = $attachment->mime_type ?? '';
    $extension = strtolower(pathinfo($attachment->original_name ?? '', PATHINFO_EXTENSION));
    $size = $attachment->size ?? 0;
    
    $iconConfig = [
        'pdf' => ['icon' => 'ti-file-text', 'color' => '#dc2626', 'bg' => '#fef2f2'],
        'doc' => ['icon' => 'ti-file-word', 'color' => '#2563eb', 'bg' => '#eff6ff'],
        'docx' => ['icon' => 'ti-file-word', 'color' => '#2563eb', 'bg' => '#eff6ff'],
        'xls' => ['icon' => 'ti-file-excel', 'color' => '#16a34a', 'bg' => '#f0fdf4'],
        'xlsx' => ['icon' => 'ti-file-excel', 'color' => '#16a34a', 'bg' => '#f0fdf4'],
        'ppt' => ['icon' => 'ti-file-powerpoint', 'color' => '#ea580c', 'bg' => '#fff7ed'],
        'pptx' => ['icon' => 'ti-file-powerpoint', 'color' => '#ea580c', 'bg' => '#fff7ed'],
        'zip' => ['icon' => 'ti-file-zip', 'color' => '#7c3aed', 'bg' => '#faf5ff'],
        'rar' => ['icon' => 'ti-file-zip', 'color' => '#7c3aed', 'bg' => '#faf5ff'],
        'png' => ['icon' => 'ti-photo', 'color' => '#0284c7', 'bg' => '#e0f2fe'],
        'jpg' => ['icon' => 'ti-photo', 'color' => '#0284c7', 'bg' => '#e0f2fe'],
        'jpeg' => ['icon' => 'ti-photo', 'color' => '#0284c7', 'bg' => '#e0f2fe'],
        'webp' => ['icon' => 'ti-photo', 'color' => '#0284c7', 'bg' => '#e0f2fe'],
    ];
    
    $isImage = in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    $config = $iconConfig[$extension] ?? ['icon' => 'ti-file', 'color' => '#64748b', 'bg' => '#f1f5f9'];
    
    $formattedSize = $size > 1048576 
        ? number_format($size / 1048576, 1) . ' MB' 
        : number_format($size / 1024, 1) . ' KB';
        
    $downloadUrl = route('admin.conversations.messages.attachments.download', [
        'conversation' => $attachment->message->conversation_id ?? 0,
        'message' => $attachment->message_id ?? 0,
        'attachment' => $attachment->id
    ]);
    $previewUrl = asset('storage/' . ($attachment->path ?? ''));
@endphp

<div {{ $attributes->merge(['class' => 'conv-att-card ' . ($compact ? 'conv-att-compact' : '')]) }}>
    @if($removable)
        <button type="button" class="conv-att-remove" title="Remove attachment">
            <i class="ti ti-x"></i>
        </button>
    @endif

    @if($isImage && !$compact)
        <div class="conv-att-image-wrapper">
            <img src="{{ $previewUrl }}" alt="{{ $attachment->original_name }}" class="conv-att-image">
            <div class="conv-att-image-overlay">
                <a href="{{ $previewUrl }}" target="_blank" class="conv-att-overlay-btn" title="Preview">
                    <i class="ti ti-eye"></i>
                </a>
                <a href="{{ $downloadUrl }}" class="conv-att-overlay-btn" title="Download">
                    <i class="ti ti-download"></i>
                </a>
            </div>
        </div>
    @endif

    <div class="conv-att-body">
        <div class="conv-att-info">
            @if(!$isImage || $compact)
                <div class="conv-att-icon" style="background: {{ $config['bg'] }}; color: {{ $config['color'] }};">
                    <i class="ti {{ $config['icon'] }}"></i>
                </div>
            @endif
            <div class="conv-att-meta">
                <p class="conv-att-name" title="{{ $attachment->original_name }}">{{ $attachment->original_name }}</p>
                <span class="conv-att-details">{{ strtoupper($extension) }} • {{ $formattedSize }}</span>
            </div>
        </div>

        @if($showActions && !$compact && !$isImage)
            <div class="conv-att-actions">
                <a href="{{ $previewUrl }}" target="_blank" class="conv-att-btn conv-att-btn-ghost" title="Preview">
                    <i class="ti ti-eye"></i>
                </a>
                <a href="{{ $downloadUrl }}" class="conv-att-btn conv-att-btn-primary" title="Download">
                    <i class="ti ti-download"></i>
                </a>
            </div>
        @endif
    </div>
</div>

@once
@push('styles')
<style>
.conv-att-card {
    position: relative;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    font-family: 'Plus Jakarta Sans', sans-serif;
    max-width: 320px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.conv-att-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}
.conv-att-compact { max-width: 240px; border-radius: 12px; }
.conv-att-remove {
    position: absolute; top: 8px; right: 8px; width: 24px; height: 24px;
    background: rgba(255, 255, 255, 0.95); border: 1px solid #e2e8f0; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; color: #ef4444;
    cursor: pointer; z-index: 10; transition: all 0.2s; opacity: 0;
}
.conv-att-card:hover .conv-att-remove { opacity: 1; }
.conv-att-remove:hover { background: #fef2f2; border-color: #fca5a5; transform: scale(1.1); }
.conv-att-remove i { font-size: 16px; stroke-width: 2; }
.conv-att-image-wrapper { position: relative; aspect-ratio: 16/10; background: #f8fafc; overflow: hidden; }
.conv-att-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
.conv-att-card:hover .conv-att-image { transform: scale(1.05); }
.conv-att-image-overlay {
    position: absolute; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px);
    display: flex; align-items: center; justify-content: center; gap: 12px; opacity: 0; transition: opacity 0.2s ease;
}
.conv-att-card:hover .conv-att-image-overlay { opacity: 1; }
.conv-att-overlay-btn {
    width: 40px; height: 40px; background: rgba(255, 255, 255, 0.95); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none;
    transition: all 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.conv-att-overlay-btn:hover { background: #f8773c; color: #ffffff; transform: scale(1.1); }
.conv-att-overlay-btn i { font-size: 20px; stroke-width: 1.8; }
.conv-att-body { padding: 16px; }
.conv-att-compact .conv-att-body { padding: 12px; }
.conv-att-info { display: flex; align-items: center; gap: 12px; }
.conv-att-icon {
    width: 40px; height: 40px; min-width: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
}
.conv-att-compact .conv-att-icon { width: 32px; height: 32px; min-width: 32px; border-radius: 8px; }
.conv-att-icon i { font-size: 22px; stroke-width: 1.5; }
.conv-att-compact .conv-att-icon i { font-size: 18px; }
.conv-att-meta { flex: 1; min-width: 0; }
.conv-att-name {
    font-size: 14px; font-weight: 600; color: #0f172a; margin: 0 0 2px 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.3;
}
.conv-att-compact .conv-att-name { font-size: 13px; }
.conv-att-details { font-size: 12px; font-weight: 500; color: #64748b; letter-spacing: 0.01em; }
.conv-att-actions { display: flex; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
.conv-att-btn {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 12px;
    border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid transparent;
}
.conv-att-btn i { font-size: 16px; stroke-width: 1.8; }
.conv-att-btn-ghost { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
.conv-att-btn-ghost:hover { background: #f1f5f9; border-color: #cbd5e1; color: #0f172a; }
.conv-att-btn-primary { background: #f8773c; color: #ffffff; box-shadow: 0 2px 8px rgba(248, 119, 60, 0.2); }
.conv-att-btn-primary:hover { background: #ea580c; box-shadow: 0 4px 12px rgba(248, 119, 60, 0.3); transform: translateY(-1px); }
</style>
@endpush
@endonce