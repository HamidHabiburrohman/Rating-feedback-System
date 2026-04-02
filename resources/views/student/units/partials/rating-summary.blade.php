@php
    $variant = $variant ?? 'sidebar';
    $breakdown = $ratingBreakdown ?? [];
    $avg = $unit->avg_rating ?? 0;
    $count = $unit->total_ratings ?? 0;
    $roundedAvg = round($avg);
    $emptyStars = 5 - $roundedAvg;
@endphp

@if ($variant === 'sidebar')
<div class="p-8 rounded-xl bg-surface-container-lowest shadow-lg border border-outline-variant/10">
    <h3 class="text-xl font-bold mb-6 text-on-surface">Rating Summary</h3>
    <div class="flex flex-col items-center mb-8">
        <span class="text-7xl font-black text-on-surface mb-2">{{ number_format($avg, 1) }}</span>
        <div class="flex text-primary mb-2 scale-125 my-2">
            @for ($i = 0; $i < $roundedAvg; $i++)
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
            @endfor
            @for ($i = 0; $i < $emptyStars; $i++)
                <span class="material-symbols-outlined">star</span>
            @endfor
        </div>
        <span class="text-on-surface-variant text-sm mt-2">Based on {{ $count }} student reviews</span>
    </div>
    @if (!empty($breakdown))
    <div class="space-y-4">
        @foreach ($breakdown as $item)
            @php $pct = min(100, ($item['score'] / 5) * 100); @endphp
            <div class="space-y-1">
                <div class="flex justify-between text-xs font-bold text-on-surface-variant uppercase tracking-tighter">
                    <span>{{ $item['label'] }}</span>
                    <span>{{ number_format($item['score'], 1) }}</span>
                </div>
                <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-5 bg-surface-container-lowest p-12 rounded-2xl shadow-lg text-center border border-outline-variant/10">
        <h3 class="text-on-surface-variant font-bold text-sm tracking-widest uppercase mb-4">Total Score</h3>
        <div class="text-8xl font-extrabold text-on-surface leading-none tracking-tighter mb-4">{{ number_format($avg, 1) }}</div>
        <div class="flex justify-center gap-1 mb-6">
            @for ($i = 0; $i < $roundedAvg; $i++)
                <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">star</span>
            @endfor
            @for ($i = 0; $i < $emptyStars; $i++)
                <span class="material-symbols-outlined text-primary text-4xl">star</span>
            @endfor
        </div>
        <p class="text-on-surface-variant font-medium">Based on {{ $count }} Student Reviews</p>
    </div>
    <div class="lg:col-span-7 space-y-6">
        @if (!empty($breakdown))
            @foreach ($breakdown as $item)
                @php $pct = min(100, ($item['score'] / 5) * 100); @endphp
                <div class="flex items-center gap-6">
                    <span class="w-32 text-sm font-bold text-on-surface-variant uppercase tracking-tighter shrink-0">{{ $item['label'] }}</span>
                    <div class="flex-1 h-3 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-10 text-sm font-bold text-right">{{ number_format($item['score'], 1) }}</span>
                </div>
            @endforeach
        @else
            <p class="text-on-surface-variant text-center">No breakdown data available.</p>
        @endif
    </div>
</div>
@endif