@props(['stats'])

<div class="bg-surface-container-low rounded-xl p-5 mb-6">
    <h3 class="font-bold text-on-surface mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">analytics</span>
        Statistik Rating
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Rata-rata & Total -->
        <div class="text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-4">
                <div>
                    <div class="text-4xl font-black text-primary">{{ number_format($stats['average'], 1) }}</div>
                    <div class="text-xs text-on-surface-variant">Rata-rata</div>
                </div>
                <div class="w-px h-10 bg-outline-variant"></div>
                <div>
                    <div class="text-4xl font-black text-on-surface">{{ $stats['total'] }}</div>
                    <div class="text-xs text-on-surface-variant">Total Rating</div>
                </div>
            </div>
        </div>
        
        <!-- Distribusi Bintang -->
        <div class="space-y-1">
            @foreach([5,4,3,2,1] as $star)
                @php $count = $stats['distribution'][$star] ?? 0; @endphp
                @php $percentage = $stats['total'] > 0 ? round($count / $stats['total'] * 100) : 0; @endphp
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-12 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm fill-icon text-primary" style="font-variation-settings:'FILL' 1">star</span>
                        <span>{{ $star }}</span>
                    </div>
                    <div class="flex-1 h-2 bg-surface-container-highest rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="w-12 text-right text-on-surface-variant text-xs">{{ $percentage }}%</div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Rata-rata per Kategori -->
    @if(!empty($stats['by_category']))
        <div class="mt-5 pt-4 border-t border-outline-variant/20">
            <h4 class="text-sm font-semibold text-on-surface-variant mb-3">Per Kategori</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($stats['by_category'] as $slug => $avg)
                    <div class="text-center">
                        <div class="text-sm font-bold text-on-surface">{{ number_format($avg, 1) }}</div>
                        <div class="text-xs text-on-surface-variant capitalize">{{ str_replace('_', ' ', $slug) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>