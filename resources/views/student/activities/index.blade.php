{{-- resources/views/student/activities/index.blade.php --}}
@extends('layouts.student.app')

@section('title', 'Recent Activities')

@section('content')
<div class="min-h-screen pt-32 pb-24 px-6 md:px-12 max-w-4xl mx-auto">
    {{-- Page Header & Back Button --}}
    <header class="mb-12">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ url()->previous() !== url()->current() }}" 
                class="w-12 h-12 flex items-center justify-center rounded-full bg-white text-on-surface hover:bg-primary-fixed transition-all group shadow-sm">
                <span class="material-symbols-outlined group-active:scale-95 transition-transform">arrow_back</span>
            </a>
            <h1 class="text-4xl font-bold tracking-tight text-on-surface">Recent Activity</h1>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
            <a href="{{ route('student.activities.index', ['filter' => 'all']) }}" 
                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all whitespace-nowrap
                    {{ $currentFilter === 'all' 
                        ? 'bg-primary text-on-primary shadow-md' 
                        : 'bg-white text-on-surface-variant font-medium hover:bg-primary-fixed' }}">
                All
                <span class="ml-1.5 text-xs opacity-80">({{ $totalRatings + $totalReports }})</span>
            </a>
            <a href="{{ route('student.activities.index', ['filter' => 'ratings']) }}" 
                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all whitespace-nowrap
                    {{ $currentFilter === 'ratings' 
                        ? 'bg-primary text-on-primary shadow-md' 
                        : 'bg-white text-on-surface-variant font-medium hover:bg-primary-fixed' }}">
                Ratings
                <span class="ml-1.5 text-xs opacity-80">({{ $totalRatings }})</span>
            </a>
            <a href="{{ route('student.activities.index', ['filter' => 'reports']) }}" 
                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all whitespace-nowrap
                    {{ $currentFilter === 'reports' 
                        ? 'bg-primary text-on-primary shadow-md' 
                        : 'bg-white text-on-surface-variant font-medium hover:bg-primary-fixed' }}">
                Reports
                <span class="ml-1.5 text-xs opacity-80">({{ $totalReports }})</span>
            </a>
        </div>
    </header>

    {{-- Activity Feed --}}
    <section class="space-y-6">
        @forelse($activities as $activity)
            @if($activity->type === 'rating')
                {{-- Rating Activity Card --}}
                <div class="group bg-white rounded-lg p-8 transition-all hover:shadow-[0_20px_40px_rgba(173,43,0,0.04)] flex gap-6 border border-slate-200">
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-light border flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-3xl">star</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                            <h3 class="text-xl font-bold text-on-surface">{{ $activity->unit_name ?? 'Unit tidak diketahui' }}</h3>
                            <div class="flex items-center gap-2">
                                @php
                                    $statusColor = match($activity->status) {
                                        'active' => 'bg-[#4CAF50]/10 text-[#4CAF50]',
                                        'edited' => 'bg-primary/10 text-primary',
                                        default => 'bg-slate-100 text-slate-500'
                                    };
                                    $statusLabel = $activity->status === 'active' ? 'Aktif' : ($activity->status === 'edited' ? 'Diedit' : ucfirst($activity->status));
                                @endphp
                                @if($activity->status !== 'active')
                                    <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                @endif
                                <span class="text-sm text-on-surface-variant">{{ $activity->created_at_human }}</span>
                            </div>
                        </div>
                        
                        {{-- Star Rating Display --}}
                        <div class="flex gap-1 mb-3">
                            @php $score = round($activity->overall_score, 1); $fullStars = floor($score); $hasHalf = ($score - $fullStars) >= 0.5; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <span class="material-symbols-outlined text-lg text-primary" style="font-variation-settings: 'FILL' 1;">star</span>
                                @elseif($hasHalf && $i == $fullStars + 1)
                                    <span class="material-symbols-outlined text-lg text-primary" style="font-variation-settings: 'FILL' 0.5;">star_half</span>
                                @else
                                    <span class="material-symbols-outlined text-lg text-slate-300">star</span>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm font-bold text-on-surface">{{ number_format($activity->overall_score, 1) }}</span>
                        </div>
                        
                        @if($activity->comment)
                            <p class="text-on-surface-variant leading-relaxed line-clamp-2">
                                "{{ Str::limit($activity->comment, 120) }}"
                            </p>
                        @else
                            <p class="text-on-surface-variant italic text-sm">Tidak ada komentar tertulis.</p>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('student.ratings.show', $activity->tracking_code) }}" 
                                class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all text-sm">
                                Lihat Detail
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($activity->type === 'report')
                {{-- Report Activity Card --}}
                @php
                    $priorityColors = [
                        'low' => 'blue',
                        'medium' => 'yellow',
                        'high' => 'orange',
                        'critical' => 'red'
                    ];
                    $priorityColor = $priorityColors[$activity->priority] ?? 'gray';
                    
                    $statusColors = [
                        'new' => 'bg-blue-100 text-blue-700',
                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                        'replied' => 'bg-purple-100 text-purple-700',
                        'resolved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'pending_preview' => 'bg-orange-100 text-orange-700'
                    ];
                    $statusColorClass = $statusColors[$activity->status] ?? 'bg-slate-100 text-slate-600';
                    $statusLabel = $activity->status ? ucfirst(str_replace('_', ' ', $activity->status)) : 'Unknown';
                @endphp
                
                <div class="group bg-white rounded-lg p-8 transition-all hover:shadow-[0_20px_40px_rgba(173,43,0,0.04)] flex gap-6 border border-slate-200 {{ $activity->status === 'resolved'}}">
                    <div class="w-14 h-14 shrink-0 rounded-2xl {{ $activity->status === 'resolved' ? 'border' : 'border' }} flex items-center justify-center {{ $activity->status === 'resolved' ? 'text-primary' : 'text-slate-500' }}">
                        <span class="material-symbols-outlined text-3xl">
                            {{ $activity->status === 'resolved' ? 'check_circle' : 'report' }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                            <h3 class="text-xl font-bold text-on-surface">{{ $activity->title }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase {{ $statusColorClass }}">
                                    {{ $statusLabel }}
                                </span>
                                <span class="text-sm text-on-surface-variant">{{ $activity->created_at_human }}</span>
                            </div>
                        </div>
                        
                        <p class="text-on-surface-variant leading-relaxed">
                            {{ Str::limit($activity->description, 100) }}
                        </p>
                        
                        @if($activity->unit_name)
                            <div class="mt-2 text-sm text-on-surface-variant/70">
                                <span class="material-symbols-outlined text-sm align-middle mr-1">location_on</span>
                                {{ $activity->unit_name }}
                            </div>
                        @endif
                        
                        @if($activity->admin_response && $activity->status === 'resolved')
                            <div class="mt-4 p-4 rounded-xl bg-surface-container-low text-sm text-on-surface-variant italic">
                                "{{ Str::limit($activity->admin_response, 150) }}"
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('student.reports.show', $activity->tracking_code) }}" 
                                class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all text-sm">
                                Lihat Laporan
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            {{-- Empty State --}}
            <div class="bg-white border border-slate-200 border-dashed rounded-lg p-16 flex flex-col items-center justify-center text-center space-y-6">
                <div class="p-6 rounded-full bg-slate-50 shadow-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-6xl">history</span>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-on-surface">No activities yet</h4>
                    <p class="text-on-surface-variant max-w-sm mx-auto mt-2">
                        @if($currentFilter === 'ratings')
                            You haven't submitted any ratings yet.
                        @elseif($currentFilter === 'reports')
                            You haven't submitted any reports yet.
                        @else
                            Start exploring units and sharing your feedback!
                        @endif
                    </p>
                </div>
                @if($currentFilter !== 'reports')
                    <a href="{{ route('student.units.index') }}" 
                        class="bg-primary text-on-primary px-8 py-3 rounded-full font-bold hover:bg-primary/90 transition-all shadow-md">
                        Browse Units
                    </a>
                @endif
            </div>
        @endforelse
    </section>

    {{-- Pagination --}}
    @if($activities->hasPages())
        <div class="mt-12">
            {{ $activities->links() }}
        </div>
    @endif

    {{-- End of Feed Indicator --}}
    @if($activities->isNotEmpty() && !$activities->hasMorePages())
        <div class="mt-16 text-center">
            <div class="inline-block p-4 rounded-full bg-surface-container-low mb-4">
                <span class="material-symbols-outlined text-outline">history</span>
            </div>
            <p class="text-on-surface-variant font-medium">You've reached the end of your recent activity.</p>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush