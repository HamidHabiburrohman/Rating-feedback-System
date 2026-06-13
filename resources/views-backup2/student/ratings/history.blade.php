@extends('layouts.student.app')

@section('title', 'Riwayat Rating Saya')

@section('content')
<div class="min-h-screen pt-32 pb-24 px-6 md:px-12 max-w-[1440px] mx-auto">
    <header class="mb-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="max-w-2xl">
                <h1 class="text-5xl font-extrabold tracking-tight text-on-surface mb-4">Riwayat Rating</h1>
                <p class="text-lg text-on-surface-variant font-medium">
                    Monitoring dan arsip review untuk seluruh unit publik di lingkungan Itenas.
                </p>
            </div>
            
            <form method="GET" action="{{ route('student.ratings.history') }}" class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-outline">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari unit atau ulasan..."
                    class="w-full bg-white border border-slate-200 rounded-full py-4 pl-14 pr-6 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium shadow-sm">
                @if(request('search'))
                    <a href="{{ route('student.ratings.history', array_filter(request()->except('search'))) }}"
                        class="absolute inset-y-0 right-5 flex items-center text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('order'))
                    <input type="hidden" name="order" value="{{ request('order') }}">
                @endif
            </form>
        </div>
    </header>

    <div class="flex flex-wrap justify-between items-center gap-4 mb-12">
        <div class="flex flex-wrap gap-3">
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" 
                class="px-8 py-3 rounded-full font-bold text-sm transition-all whitespace-nowrap
                    {{ empty(request('status')) 
                        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20' 
                        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" 
                class="px-8 py-3 rounded-full font-bold text-sm transition-all whitespace-nowrap
                    {{ request('status') === 'active' 
                        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20' 
                        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Aktif
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'edited']) }}" 
                class="px-8 py-3 rounded-full font-bold text-sm transition-all whitespace-nowrap
                    {{ request('status') === 'edited' 
                        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20' 
                        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Diedit
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'archived']) }}" 
                class="px-8 py-3 rounded-full font-bold text-sm transition-all whitespace-nowrap
                    {{ request('status') === 'archived' 
                        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20' 
                        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Diarsipkan
            </a>
        </div>

        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-sm">sort</span>
            <select name="sort" onchange="window.location.href=this.value" 
                class="bg-white border border-slate-200 rounded-full px-5 py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary/20 cursor-pointer">
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc']) }}" 
                        {{ (request('sort') ?? 'created_at') === 'created_at' && request('order') !== 'asc' ? 'selected' : '' }}>
                    Terbaru
                </option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc']) }}"
                        {{ request('sort') === 'created_at' && request('order') === 'asc' ? 'selected' : '' }}>
                    Terlama
                </option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'overall_score', 'order' => 'desc']) }}"
                        {{ request('sort') === 'overall_score' && request('order') !== 'asc' ? 'selected' : '' }}>
                    Skor Tertinggi
                </option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'overall_score', 'order' => 'asc']) }}"
                        {{ request('sort') === 'overall_score' && request('order') === 'asc' ? 'selected' : '' }}>
                    Skor Terendah
                </option>
            </select>
        </div>
    </div>

    @if(count($ratings['data'] ?? $ratings) > 0)
        <div class="flex flex-col gap-6">
            @foreach($ratings['data'] ?? $ratings as $rating)
                @php 
                    if (is_array($rating)) $rating = (object) $rating;
                    $statusColor = match($rating->status) {
                        'active' => 'bg-[#4CAF50]',
                        'edited' => 'bg-primary',
                        'archived' => 'bg-slate-400',
                        default => 'bg-outline-variant'
                    };
                    $statusLabel = ucfirst($rating->status);
                    if ($rating->status === 'active') $statusLabel = 'Aktif';
                    if ($rating->status === 'edited') $statusLabel = 'Diedit';
                    if ($rating->status === 'archived') $statusLabel = 'Diarsipkan';
                @endphp
                <div class="group bg-white rounded-lg p-6 flex flex-col md:flex-row items-start md:items-center gap-6 transition-all duration-300 hover:shadow-lg border border-slate-200">                    
                    <div class="flex-1 space-y-2 pl-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-xl font-bold text-on-surface">{{ $rating->unit->name }}</h3>
                            <span class="text-[10px] uppercase tracking-widest font-bold px-2 py-0.5 rounded
                                @if($rating->status === 'active') bg-[#4CAF50]/10 text-[#4CAF50]
                                @elseif($rating->status === 'edited') bg-primary/10 text-primary
                                @else bg-slate-100 text-slate-500 @endif">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-1 mb-2">
                            @php $score = round($rating->overall_score, 1); $fullStars = floor($score); $hasHalf = ($score - $fullStars) >= 0.5; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <span class="material-symbols-outlined text-sm text-primary" style="font-variation-settings: 'FILL' 1;">star</span>
                                @elseif($hasHalf && $i == $fullStars + 1)
                                    <span class="material-symbols-outlined text-sm text-primary" style="font-variation-settings: 'FILL' 0.5;">star</span>
                                @else
                                    <span class="material-symbols-outlined text-sm text-slate-300" style="font-variation-settings: 'FILL' 0;">star</span>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm font-bold text-on-surface">{{ number_format($rating->overall_score, 1) }}</span>
                        </div>
                        
                        @if($rating->comment)
                            <p class="text-on-surface-variant text-sm line-clamp-1 max-w-2xl">
                                "{{ Str::limit($rating->comment, 100) }}"
                            </p>
                        @else
                            <p class="text-on-surface-variant text-sm italic">Tidak ada komentar tertulis.</p>
                        @endif
                    </div>
                    
                    <div class="flex flex-col items-end gap-4 min-w-[140px]">
                        <span class="text-xs font-semibold text-on-surface-variant/70 uppercase tracking-tighter">
                            {{ \Carbon\Carbon::parse($rating->created_at)->translatedFormat('d M Y') }}
                        </span>
                        <a href="{{ route('student.ratings.show', $rating->tracking_code) }}" 
                            class="flex items-center gap-2 text-primary font-bold text-sm group/link">
                            Lihat Detail
                            <span class="material-symbols-outlined text-sm transition-transform group-hover/link:translate-x-1">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-slate-200 border-dashed rounded-lg p-12 flex flex-col items-center justify-center text-center space-y-4">
            <div class="p-6 rounded-full bg-slate-50 shadow-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl">rate_review</span>
            </div>
            <div>
                <h4 class="text-xl font-bold text-on-surface">Belum ada rating yang diberikan</h4>
                <p class="text-on-surface-variant max-w-xs mx-auto mt-1">Silakan beri rating pada unit yang tersedia.</p>
            </div>
            <a href="{{ route('student.units.index') }}" 
                class="bg-white border border-slate-200 px-8 py-3 rounded-full font-bold text-on-surface-variant hover:bg-slate-50 transition-colors">
                Lihat Unit
            </a>
        </div>
    @endif
</div>
@endsection