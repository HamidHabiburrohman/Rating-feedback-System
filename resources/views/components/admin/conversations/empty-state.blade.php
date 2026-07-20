@props([
    'icon' => 'ti-inbox',
    'title' => 'No Data',
    'description' => 'Nothing to show here yet.',
    'actionUrl' => null,
    'actionText' => 'Add New',
    'iconColor' => '#f8773c',
    'iconBg' => '#fff5f0',
    'compact' => false,
])

<div {{ $attributes->merge(['class' => 'conv-empty-state ' . ($compact ? 'conv-empty-compact' : '')]) }}>
    <div class="conv-empty-icon-wrapper" style="background: {{ $iconBg }}; box-shadow: 0 8px 24px {{ $iconBg }};">
        <i class="ti {{ $icon }}" style="color: {{ $iconColor }};"></i>
    </div>
    
    <h3 class="conv-empty-title">{{ $title }}</h3>
    <p class="conv-empty-desc">{{ $description }}</p>
    
    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="conv-empty-cta">
            <i class="ti ti-plus"></i>
            <span>{{ $actionText }}</span>
        </a>
    @endif
</div>

@once
@push('styles')
<style>
    .conv-empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 64px 32px; font-family: 'Plus Jakarta Sans', sans-serif; max-width: 420px; margin: 0 auto; }
    .conv-empty-compact { padding: 40px 24px; max-width: 320px; }
    
    .conv-empty-icon-wrapper { width: 88px; height: 88px; border-radius: 28px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .conv-empty-compact .conv-empty-icon-wrapper { width: 64px; height: 64px; border-radius: 20px; margin-bottom: 16px; }
    .conv-empty-state:hover .conv-empty-icon-wrapper { transform: translateY(-4px) scale(1.02); }
    
    .conv-empty-icon-wrapper i { font-size: 40px; stroke-width: 1.5; }
    .conv-empty-compact .conv-empty-icon-wrapper i { font-size: 28px; }
    
    .conv-empty-title { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0; letter-spacing: -0.02em; line-height: 1.2; }
    .conv-empty-compact .conv-empty-title { font-size: 16px; }
    
    .conv-empty-desc { font-size: 14px; font-weight: 400; color: #64748b; margin: 0 0 28px 0; line-height: 1.6; max-width: 320px; }
    .conv-empty-compact .conv-empty-desc { font-size: 13px; margin-bottom: 20px; }
    
    .conv-empty-cta { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #f8773c; color: #ffffff; border-radius: 12px; font-size: 14px; font-weight: 600; text-decoration: none; box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
    .conv-empty-cta:hover { background: #ea580c; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(248, 119, 60, 0.35); }
    .conv-empty-cta i { font-size: 18px; stroke-width: 2; }
</style>
@endpush
@endonce