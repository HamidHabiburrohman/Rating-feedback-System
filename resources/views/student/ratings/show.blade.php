@extends('layouts.student.app')

@section('title', 'Detail Rating - ' . $rating->tracking_code)

@section('content')
<div class="min-h-screen pt-32 pb-20 px-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('student.ratings.history') }}" class="text-on-surface-variant hover:text-primary flex items-center gap-1 text-sm">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali ke Riwayat
        </a>
    </div>
    
    <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10 mb-6">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        @if($rating->status === 'active') bg-green-100 text-green-700
                        @elseif($rating->status === 'edited') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ ucfirst($rating->status) }}
                    </span>
                    <span class="text-xs text-on-surface-variant font-mono">{{ $rating->tracking_code }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-on-surface">
                    {{ $rating->unit->name }}
                </h1>
                <div class="flex items-center gap-3 mt-2">
                    <div class="flex text-primary">
                        @php $score = round($rating->overall_score); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined {{ $i <= $score ? 'fill-icon' : '' }}"
                                  style="font-variation-settings: 'FILL' {{ $i <= $score ? 1 : 0 }}">
                                star
                            </span>
                        @endfor
                    </div>
                    <span class="text-2xl font-black">{{ number_format($rating->overall_score, 1) }}</span>
                </div>
            </div>
            
            <div class="text-right text-sm text-on-surface-variant">
                <div>Dibuat: {{ $rating->created_at->format('d M Y H:i') }}</div>
                @if($rating->last_edited_at)
                    <div>Terakhir edit: {{ $rating->last_edited_at->format('d M Y H:i') }}</div>
                @endif
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-outline-variant/10">
            @if($rating->student_identifier === auth('student')->user()->student_identifier)
                @if($canEdit)
                    <a href="{{ route('student.ratings.edit', $rating->tracking_code) }}" 
                       class="px-5 py-2 rounded-full bg-primary/10 text-primary font-medium hover:bg-primary/20 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">edit</span>
                        Edit Rating
                    </a>
                @endif
                
                @if($canReport)
                    <a href="{{ route('student.reports.create', $rating->id) }}" 
                       class="px-5 py-2 rounded-full bg-error/10 text-error font-medium hover:bg-error/20 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">flag</span>
                        Laporkan Rating
                    </a>
                @endif
            @endif
        </div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10 mb-6">
        <h2 class="text-lg font-bold text-on-surface mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined">category</span>
            Detail Kategori
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($rating->scores as $score)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium">{{ $score->category->name }}</span>
                        <span class="font-bold">{{ $score->score }}/5</span>
                    </div>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-sm {{ $i <= $score->score ? 'fill-icon text-primary' : 'text-outline' }}"
                                  style="font-variation-settings: 'FILL' {{ $i <= $score->score ? 1 : 0 }}">
                                star
                            </span>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    @if($rating->comment)
        <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10 mb-6">
            <h2 class="text-lg font-bold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined">comment</span>
                Komentar
            </h2>
            <p class="text-on-surface leading-relaxed">{{ $rating->comment_display }}</p>
            @if($rating->is_comment_censored)
                <p class="text-xs text-on-surface-variant mt-2 italic">*Komentar telah disensor oleh admin</p>
            @endif
        </div>
    @endif
    
    @if($rating->adminReply)
        <div class="bg-primary/5 rounded-2xl p-6 md:p-8 border-l-4 border-primary mb-6">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-primary">support_agent</span>
                <span class="font-bold text-primary">Balasan Admin</span>
                <span class="text-xs text-on-surface-variant">{{ $rating->adminReply->created_at->format('d M Y H:i') }}</span>
            </div>
            <p class="text-on-surface">{{ $rating->adminReply->reply }}</p>
        </div>
    @endif
    
    {{-- Statistik Unit --}}
    <div class="bg-surface-container-low rounded-xl p-5 mt-8">
        <h3 class="font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">analytics</span>
            Statistik Rating Unit Ini
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
</div>
@endsection