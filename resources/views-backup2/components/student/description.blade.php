@props(['unit'])

@if($unit->description)
<div class="section-block">
    <div class="section-header">
        <div class="section-icon" style="background:#dbeafe;">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h12"/>
            </svg>
        </div>
        <span class="section-title">Deskripsi</span>
    </div>
    <div class="section-body">
        <p class="description-text" id="descText">{{ $unit->description }}</p>
        <button class="read-more-btn" id="readMoreBtn">Baca selengkapnya ↓</button>
    </div>
</div>
@endif