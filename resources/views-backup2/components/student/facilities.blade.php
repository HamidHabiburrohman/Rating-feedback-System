@props(['unit'])

@if($unit->facilities && $unit->facilities->count())
<div class="section-block">
    <div class="section-header">
        <div class="section-icon" style="background:#ede9fe;">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#6d28d9" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <span class="section-title">Fasilitas & Layanan</span>
    </div>

    <div class="facilities-grid">
        @foreach($unit->facilities as $facility)
            <div class="facility-item">
                @if($facility->icon_key)
                    <i class="{{ $facility->icon_key }}" style="color: var(--c-accent); font-size: 14px;"></i>
                @else
                    <span class="facility-dot"></span>
                @endif
                <span class="facility-name">{{ $facility->name }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif