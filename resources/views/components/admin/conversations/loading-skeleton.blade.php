{{-- resources/views/components/admin/conversations/loading-skeleton.blade.php --}}
@props([
    'type' => 'chat',
    'count' => 3,
])
@php
    $types = ['list', 'header', 'chat', 'composer', 'participants', 'attachments', 'sidebar'];
    $currentType = in_array($type, $types) ? $type : 'chat';
@endphp

<div {{ $attributes->merge(['class' => 'conv-skeleton-wrapper']) }}>
    @if($currentType === 'list' || $currentType === 'sidebar')
        @for($i = 0; $i < $count; $i++)
            <div class="conv-skel-list-item">
                <div class="conv-skel conv-skel-circle"></div>
                <div class="conv-skel-list-content">
                    <div class="conv-skel conv-skel-line conv-skel-w-60"></div>
                    <div class="conv-skel conv-skel-line conv-skel-w-90 conv-skel-mt-8"></div>
                    <div class="conv-skel conv-skel-line conv-skel-w-40 conv-skel-mt-8"></div>
                </div>
                <div class="conv-skel conv-skel-line conv-skel-w-10 conv-skel-h-12 conv-skel-align-start"></div>
            </div>
        @endfor
    @elseif($currentType === 'chat')
        {{-- Message bubble skeletons ONLY. Uses real message CSS classes for layout parity. --}}
        @for($i = 0; $i < $count; $i++)
            @php $isOwn = $i % 2 !== 0; @endphp
            <div class="conv-msg-row {{ $isOwn ? 'conv-msg-own' : 'conv-msg-incoming' }} conv-skel-row">
                @if(!$isOwn)
                    <div class="conv-msg-avatar-col">
                        <div class="conv-skel conv-skel-circle" style="width:32px;height:32px;"></div>
                    </div>
                @else
                    <div class="conv-msg-avatar-col"></div>
                @endif
                <div class="conv-msg-content-col">
                    @if(!$isOwn)
                        <div class="conv-skel conv-skel-line" style="width:80px;height:12px;margin-bottom:6px;"></div>
                    @endif
                    <div class="conv-msg-bubble {{ $isOwn ? 'conv-msg-bubble-own' : '' }}">
                        <div class="conv-skel conv-skel-line" style="width:100%;height:14px;"></div>
                        <div class="conv-skel conv-skel-line" style="width:{{ $isOwn ? '60' : '70' }}%;height:14px;margin-top:8px;"></div>
                        @if($i === 0)
                            <div class="conv-skel conv-skel-line" style="width:45%;height:14px;margin-top:8px;"></div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    @elseif($currentType === 'participants')
        @for($i = 0; $i < $count; $i++)
            <div class="conv-skel-participant">
                <div class="conv-skel conv-skel-circle conv-skel-w-32 conv-skel-h-32"></div>
                <div class="conv-skel-participant-content">
                    <div class="conv-skel conv-skel-line conv-skel-w-60 conv-skel-h-14"></div>
                    <div class="conv-skel conv-skel-line conv-skel-w-40 conv-skel-h-12 conv-skel-mt-6"></div>
                </div>
            </div>
        @endfor
    @elseif($currentType === 'attachments')
        <div class="conv-skel-attachments">
            @for($i = 0; $i < $count; $i++)
                <div class="conv-skel-att-card">
                    <div class="conv-skel conv-skel-square"></div>
                    <div class="conv-skel-att-content">
                        <div class="conv-skel conv-skel-line conv-skel-w-80 conv-skel-h-14"></div>
                        <div class="conv-skel conv-skel-line conv-skel-w-50 conv-skel-h-12 conv-skel-mt-6"></div>
                    </div>
                </div>
            @endfor
        </div>
    @endif
</div>

@once
@push('styles')
<style>
.conv-skel {
    background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 37%, #f1f5f9 63%);
    background-size: 400% 100%;
    animation: conv-skel-shimmer 1.4s ease infinite;
    border-radius: 6px;
}

@keyframes conv-skel-shimmer {
    0% { background-position: 100% 50%; }
    100% { background-position: 0 50%; }
}

.conv-skel-circle { border-radius: 50%; }
.conv-skel-square { border-radius: 12px; aspect-ratio: 1 / 1; width: 48px; }
.conv-skel-line { height: 14px; }
.conv-skel-w-10 { width: 10%; } .conv-skel-w-20 { width: 20%; } .conv-skel-w-30 { width: 30%; }
.conv-skel-w-40 { width: 40%; } .conv-skel-w-50 { width: 50%; } .conv-skel-w-60 { width: 60%; }
.conv-skel-w-70 { width: 70%; } .conv-skel-w-80 { width: 80%; } .conv-skel-w-90 { width: 90%; } .conv-skel-w-100 { width: 100%; }
.conv-skel-h-10 { height: 10px; } .conv-skel-h-12 { height: 12px; } .conv-skel-h-14 { height: 14px; }
.conv-skel-h-20 { height: 20px; } .conv-skel-h-32 { height: 32px; } .conv-skel-h-36 { height: 36px; }
.conv-skel-h-40 { height: 40px; }
.conv-skel-mt-6 { margin-top: 6px; } .conv-skel-mt-8 { margin-top: 8px; }
.conv-skel-mb-6 { margin-bottom: 6px; } .conv-skel-ml-auto { margin-left: auto; }
.conv-skel-align-start { align-self: flex-start; margin-top: 4px; }

.conv-skeleton-wrapper { display: flex; flex-direction: column; gap: 16px; font-family: 'Plus Jakarta Sans', sans-serif; }
.conv-skel-list-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px; border-radius: 12px; }
.conv-skel-list-item .conv-skel-circle { width: 48px; height: 48px; flex-shrink: 0; }
.conv-skel-list-content { flex: 1; min-width: 0; }
.conv-skel-participant { display: flex; align-items: center; gap: 12px; padding: 8px; }
.conv-skel-participant-content { flex: 1; }
.conv-skel-attachments { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
.conv-skel-att-card { display: flex; align-items: center; gap: 12px; padding: 16px; border: 1px solid #f1f5f9; border-radius: 16px; }
.conv-skel-att-content { flex: 1; min-width: 0; }

/* Skeleton rows inherit real message layout — no independent widths */
.conv-skel-row { opacity: 0.7; }
</style>
@endpush
@endonce