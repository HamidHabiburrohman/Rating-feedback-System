@extends('layouts.student.app')

@section('content')
    <div class="pt-28 pb-20 px-8 max-w-7xl mx-auto flex flex-col lg:flex-row gap-10">
        <div class="flex-1 space-y-10">

            <section
                class="bg-surface-container-lowest rounded-lg p-8 border flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-linear-to-br from-primary to-secondary-container opacity-[0.03] rounded-full -mr-20 -mt-20">
                </div>
                <div class="relative group">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-lg overflow-hidden hrink-0 border">
                        <img class="w-full h-full object-cover" src="{{ $profile->photo_url }}" alt="{{ $profile->name }}">
                    </div>
                    <a href="{{ route('student.profile.edit') }}"
                        class="absolute -bottom-2 -right-2 w-9 h-9 bg-primary text-white rounded-full shadow-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                </div>

                <div class="text-center md:text-left flex-1">
                    <h1 class="text-4xl font-bold text-on-surface tracking-tight mb-2">{{ $profile->name }}</h1>
                    <p class="text-on-surface-variant max-w-md text-sm leading-relaxed mb-6">
                        {{ $profile->bio ?? 'Student at Itenas, passionate about campus digital governance.' }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span
                            class="px-4 py-1.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-bold">Class
                            of {{ $profile->class_year ?? 'N/A' }}</span>
                        <span class="px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-bold">Student ID:
                            {{ $profile->student_identifier }}</span>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-surface-container-lowest p-6 rounded-lg border">
                    <span class="material-symbols-outlined text-primary mb-4">analytics</span>
                    <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1">Score</h3>
                    <div class="text-3xl font-extrabold text-on-surface tracking-tighter">{{ number_format($score) }}</div>
                    <div class="mt-2 text-[10px] text-primary font-bold">Total Score</div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-lg border">
                    <span class="material-symbols-outlined text-primary mb-4">check_circle</span>
                    <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1">Resolved</h3>
                    <div class="text-3xl font-extrabold text-on-surface tracking-tighter">{{ $resolvedCount }}</div>
                    <div class="mt-2 text-[10px] text-on-surface-variant font-medium">Success Rate</div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-lg border">
                    <span class="material-symbols-outlined text-primary mb-4">thumb_up</span>
                    <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-1">Ratings</h3>
                    <div class="text-3xl font-extrabold text-on-surface tracking-tighter">{{ $stats['total_ratings'] ?? 0 }}
                    </div>
                    <div class="mt-2 text-[10px] text-on-surface-variant font-medium">{{ $stats['average_rating'] ?? 0 }}
                        Avg. Satisfaction</div>
                </div>
            </section>

            <section class="space-y-6">
                <div class="flex items-end justify-between px-2">
                    <h2 class="text-2xl font-bold text-on-surface tracking-tight">Recent Activities</h2>
                    @if($totalActivities > 3)
                        <div class="mt-4 text-center">
                            <a href="{{ route('student.activities.index') }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
                                View all activities
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    @endif
                </div>
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="bg-surface-container-lowest p-6 rounded-lg soft-border flex gap-6 items-start border">
                            <div class="shrink-0">
                                <div
                                    class="w-12 h-12 rounded-full border border-gray-100 flex items-center justify-center text-primary">
                                    <span
                                        class="material-symbols-outlined">{{ $activity->type === 'rating' ? 'star' : 'report' }}</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="px-3 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    </div>
                                    <span
                                        class="text-[10px] font-bold text-on-surface-variant/60 uppercase">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="font-bold text-on-surface">{{ $activity->unit_name ?? 'Unknown Unit' }}</h4>
                                <p class="text-sm text-on-surface-variant leading-relaxed mb-4">{{ $activity->description }}</p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="px-3 py-1 rounded-full bg-surface-container-high text-[11px] font-bold text-on-surface-variant flex items-center gap-1">
                                        <span
                                            class="material-symbols-outlined text-[12px]!">{{ $activity->type === 'rating' ? 'rate_review' : 'assignment_late' }}</span>
                                        {{ $activity->type === 'rating' ? ($activity->unit_name ?? 'Unit Rating') : 'Report' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-on-surface-variant">No contributions yet.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="w-full lg:w-[380px] space-y-10">
            <div class="bg-surface-container-lowest p-8 rounded-lg border">
                <h3 class="text-lg font-bold text-on-surface tracking-tight mb-6">Weekly Engagement</h3>
                <div class="flex items-end justify-between h-32 gap-2 px-2">
                    @php
                        $maxVal = max($weeklyEngagement) ?: 1;
                        $days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
                    @endphp
                    @foreach($weeklyEngagement as $index => $value)
                        <div class="w-full bg-surface-container-high rounded-t-md hover:bg-primary transition-colors relative group"
                            style="height: {{ ($value / $maxVal) * 100 }}%">
                            @if($value > 0)
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold">{{ $value }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div
                    class="flex justify-between mt-4 px-2 text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-tighter">
                    @foreach($days as $day)
                        <span>{{ $day }}</span>
                    @endforeach
                </div>
            </div>

            <div class="bg-surface-container-lowest p-8 rounded-lg border">
                <h3 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-6">Contact Details</h3>
                <div class="space-y-4 mb-4">
                    <div class="flex items-center gap-3 text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px]! text-primary">mail</span>
                        <span>{{ $profile->email }}</span>
                    </div>
                    @if($profile->phone)
                        <div class="flex items-center gap-3 text-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px]! text-primary">call</span>
                            <span>{{ $profile->phone }}</span>
                        </div>
                    @endif
                </div>
        </aside>
    </div>
@endsection