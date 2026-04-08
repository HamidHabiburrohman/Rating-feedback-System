{{-- resources/views/student/profile/show.blade.php --}}
@extends('layouts.student.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
            <div
                class="lg:col-span-8 flex flex-col md:flex-row items-center md:items-end gap-8 bg-white p-8 md:p-12 rounded-2xl shadow-xl">
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-linear-to-tr from-primary to-secondary-container rounded-full blur opacity-25 group-hover:opacity-40 transition duration-1000">
                    </div>
                    <img class="relative w-40 h-40 md:w-56 md:h-56 rounded-full object-cover border-4 border-white shadow-xl"
                        src="{{ $profile->photo_url }}"
                        alt="{{ $profile->name }}">
                </div>
                <div class="flex-1 text-center md:text-left">
                    <span
                        class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4 inline-block">
                        Student ID: {{ $profile->student_identifier }}
                    </span>
                    <h1 class="text-4xl md:text-6xl font-bold tracking-tighter text-on-surface mb-2">{{ $profile->name }}
                    </h1>
                    <p class="text-xl text-on-surface-variant font-medium">
                        {{ $profile->major ?? 'Major not set' }} • {{ $profile->class_year ?? 'Class not set' }}
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3 justify-center md:justify-start">
                        <a href="{{ route('student.profile.edit') }}"
                            class="bg-red-600 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-red-700 transition-transform hover:scale-105">
                            Edit Profile
                        </a>
                        <a href="{{ route('student.profile.sessions') }}"
                            class="bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-bold hover:bg-gray-300 transition-colors">
                            Manage Sessions
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-surface-container-low p-8 rounded-2xl h-full flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface mb-4">Contact Information</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-on-surface-variant">
                                <span class="material-symbols-outlined text-primary">mail</span>
                                <span class="text-sm font-medium">{{ $profile->email }}</span>
                            </li>
                            @if($profile->phone ?? false)
                                <li class="flex items-center gap-3 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-primary">call</span>
                                    <span class="text-sm font-medium">{{ $profile->phone }}</span>
                                </li>
                            @endif
                            @if($profile->location ?? false)
                                <li class="flex items-center gap-3 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-primary">location_on</span>
                                    <span class="text-sm font-medium">{{ $profile->location }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="mt-8 pt-6 border-t border-outline-variant/10">
                        <p class="text-xs text-on-surface-variant italic">
                            {{ $profile->bio ?? 'No bio added yet.' }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-surface-container-low p-8 rounded-2xl flex items-center gap-6 group hover:bg-surface-container transition-colors duration-500">
                <div
                    class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary text-3xl">star_rate</span>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-on-surface">{{ $stats['total_ratings'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-on-surface-variant">Total Ratings</div>
                </div>
            </div>
            <div
                class="bg-surface-container-low p-8 rounded-2xl flex items-center gap-6 group hover:bg-surface-container transition-colors duration-500">
                <div
                    class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary text-3xl">assignment_late</span>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-on-surface">{{ $stats['total_reports'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-on-surface-variant">Total Reports</div>
                </div>
            </div>
            <div
                class="bg-surface-container-low p-8 rounded-2xl flex items-center gap-6 group hover:bg-surface-container transition-colors duration-500">
                <div
                    class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-on-surface">{{ $stats['member_since'] ?? '' }}</div>
                    <div class="text-sm font-semibold text-on-surface-variant">Member Since</div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 bg-white p-8 md:p-12 rounded-2xl shadow-xl">
                <div class="flex justify-between items-center mb-10">
                    <h2 class="text-2xl font-bold tracking-tight text-on-surface">Recent Activity</h2>
                    <a href="#" class="text-primary font-bold text-sm flex items-center gap-1 hover:underline">
                        View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                @php
                    $recentRatings = $profile->ratings()->with('unit')->latest()->limit(3)->get();
                    $recentReports = $profile->reports()->with('rating')->latest()->limit(3)->get();
                    $activities = collect();
                    foreach ($recentRatings as $rating) {
                        $activities->push((object) [
                            'type' => 'rating',
                            'title' => 'Rating Submitted',
                            'description' => "You rated {$rating->unit->name}",
                            'score' => $rating->overall_score,
                            'created_at' => $rating->created_at,
                        ]);
                    }
                    foreach ($recentReports as $report) {
                        $activities->push((object) [
                            'type' => 'report',
                            'title' => 'Report Status Updated',
                            'description' => "Report for rating #{$report->rating_id} is {$report->status}",
                            'created_at' => $report->created_at,
                        ]);
                    }
                    $activities = $activities->sortByDesc('created_at')->take(5);
                @endphp

                <div
                    class="space-y-12 relative before:absolute before:left-[11px] before:top-2 before:bottom-0 before:w-[2px] before:bg-surface-container">
                    @forelse($activities as $activity)
                        <div class="relative pl-12">
                            <div
                                class="absolute left-0 top-1 w-6 h-6 rounded-full 
                                {{ $activity->type === 'rating' ? 'bg-primary' : 'bg-tertiary' }} border-4 border-white shadow-md z-10">
                            </div>
                            <div class="bg-surface-container-low p-6 rounded-xl">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-on-surface">{{ $activity->title }}</h4>
                                    <span class="text-xs font-bold text-on-surface-variant bg-white px-3 py-1 rounded-full">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-on-surface-variant text-sm leading-relaxed mb-3">{{ $activity->description }}</p>
                                @if($activity->type === 'rating')
                                    <div class="flex items-center gap-1 text-orange-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span
                                                class="material-symbols-outlined text-lg">{{ $i <= $activity->score ? 'star' : 'star' }}</span>
                                        @endfor
                                        <span class="ml-2 text-xs font-bold text-on-surface-variant">{{ $activity->score }} /
                                            5.0</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-on-surface-variant">No recent activity yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <div class="bg-surface-container-low p-8 rounded-2xl">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Frequently Visited Units</h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $frequentUnits = $profile->ratings()
                                ->with('unit')
                                ->select('unit_id', DB::raw('count(*) as total'))
                                ->groupBy('unit_id')
                                ->orderBy('total', 'desc')
                                ->limit(6)
                                ->get();
                        @endphp
                        @forelse($frequentUnits as $rating)
                            <span
                                class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-4 py-2 rounded-full text-xs font-bold hover:scale-105 transition-transform cursor-pointer">
                                {{ $rating->unit->name }}
                            </span>
                        @empty
                            <span class="text-on-surface-variant text-sm">No units visited yet.</span>
                        @endforelse
                    </div>
                </div>

                <div
                    class="bg-linear-to-br from-primary to-primary-container p-8 rounded-2xl text-white shadow-xl shadow-primary/20">
                    <h3 class="text-xl font-bold mb-2">Need help with a unit?</h3>
                    <p class="text-sm opacity-90 mb-6">Submit a quick report or request assistance directly from the unit
                        managers.</p>
                    <a href="{{ route('student.reports.create', ['rating' => 0]) }}"
                        class="flex w-full bg-white text-primary py-3 rounded-full font-bold items-center justify-center gap-2 hover:bg-gray-100 transition-all text-center">
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                        New Request
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection