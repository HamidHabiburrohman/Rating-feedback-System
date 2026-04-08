@extends('layouts.student.app')

@section('title', 'Riwayat Laporan Saya')

@section('content')
    <div class="min-h-screen pt-32 pb-20 px-4 md:px-12 lg:px-24 max-w-7xl mx-auto">
        {{-- Header Section --}}
        <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-5xl font-extrabold text-on-surface tracking-tighter">Riwayat Laporan</h1>
                <p class="text-on-surface-variant text-lg">Pantau dan kelola seluruh laporan unit publik Itenas.</p>
            </div>

            {{-- Search Form --}}
            <form method="GET" action="{{ route('student.reports.history') }}" class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-outline">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Laporan atau Judul..."
                    class="w-full bg-white border border-slate-200 rounded-full py-4 pl-14 pr-6 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium shadow-sm">
                @if(request('search') || request('status'))
                    <a href="{{ route('student.reports.history') }}"
                        class="absolute inset-y-0 right-5 flex items-center text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                @endif
                {{-- Simpan status filter saat search --}}
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            </form>
        </header>

        {{-- Filters Section --}}
        <section class="mb-10 overflow-x-auto no-scrollbar flex items-center gap-3 py-2">
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="px-8 py-3 rounded-full font-semibold transition-all active:scale-95 whitespace-nowrap
                                {{ empty(request('status'))
        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20'
        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'new']) }}"
                class="px-8 py-3 rounded-full font-semibold transition-all active:scale-95 whitespace-nowrap
                                {{ request('status') === 'new'
        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20'
        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Baru
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}"
                class="px-8 py-3 rounded-full font-semibold transition-all active:scale-95 whitespace-nowrap
                                {{ request('status') === 'in_progress'
        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20'
        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Diproses
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'replied']) }}"
                class="px-8 py-3 rounded-full font-semibold transition-all active:scale-95 whitespace-nowrap
                                {{ request('status') === 'replied'
        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20'
        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Ditanggapi
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'resolved']) }}"
                class="px-8 py-3 rounded-full font-semibold transition-all active:scale-95 whitespace-nowrap
                                {{ request('status') === 'resolved'
        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20'
        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Selesai
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}"
                class="px-8 py-3 rounded-full font-semibold transition-all active:scale-95 whitespace-nowrap
                                {{ request('status') === 'rejected'
        ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20'
        : 'bg-white text-on-surface-variant border border-slate-200 hover:border-primary hover:text-primary' }}">
                Ditolak
            </a>
        </section>

        {{-- Reports Bento List --}}
        @if(count($reports['data'] ?? $reports) > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($reports['data'] ?? $reports as $report)
                    @php
                        if (is_array($report)) {
                            $report = (object) $report;
                        }
                        if (isset($report->unit) && is_array($report->unit)) {
                            $report->unit = (object) $report->unit;
                        }
                    @endphp
                    {{-- Card Item --}}
                    <div
                        class="bg-surface-container-lowest border border-slate-200 rounded-lg p-8 hover:shadow-[0_20px_40px_rgba(173,43,0,0.06)] transition-all duration-500 group flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex-1 space-y-4">
                            {{-- Badges & Meta Row --}}
                            <div class="flex flex-wrap items-center gap-3">
                                {{-- Status Badge --}}
                                <span class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest
                                                                                @if($report->status === 'new') bg-blue-100 text-blue-800
                                                                                @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                                                @elseif($report->status === 'replied') bg-purple-100 text-purple-800
                                                                                @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                                                                @elseif($report->status === 'rejected') bg-red-100 text-red-800
                                                                                @else bg-slate-100 text-slate-800 @endif">
                                    {{ $report->status_label ?? ucfirst($report->status) }}
                                </span>

                                {{-- Tracking Code --}}
                                <span class="flex items-center gap-1.5 text-on-surface-variant text-sm">
                                    <span class="material-symbols-outlined text-sm">fingerprint</span>
                                    {{ $report->tracking_code }}
                                </span>

                                {{-- Relative Time --}}
                                <span class="flex items-center gap-1.5 text-on-surface-variant text-sm">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    {{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Title & Unit/Priority --}}
                            <div>
                                <h3 class="text-2xl font-bold text-on-surface group-hover:text-primary transition-colors">
                                    {{ $report->title }}
                                </h3>
                                <div class="flex items-center gap-3 mt-2 flex-wrap">
                                    {{-- Unit Name --}}
                                    <span
                                        class="px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-semibold">
                                        {{ $report->unit->name ?? 'Unit tidak ditemukan' }}
                                    </span>

                                    {{-- Priority Badge --}}
                                    <span class="flex items-center gap-1 text-xs font-bold uppercase
                                                                                    @if($report->priority === 'critical') text-red-600
                                                                                    @elseif($report->priority === 'high') text-orange-600
                                                                                    @elseif($report->priority === 'medium') text-yellow-600
                                                                                    @else text-blue-600 @endif">
                                        <span class="material-symbols-outlined text-sm">priority_high</span>
                                        {{ $report->priority_label ?? ucfirst($report->priority) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Description Preview (optional, sesuai original) --}}
                            <p class="text-sm text-on-surface-variant line-clamp-2">
                                {{ Str::limit($report->description, 100) }}
                            </p>
                        </div>

                        {{-- Right Side: Icon & Detail Link --}}
                        <div class="flex items-center gap-6">

                            {{-- Detail Link --}}
                            <a href="{{ route('student.reports.show', $report->tracking_code) }}"
                                class="flex items-center gap-2 text-primary font-bold hover:gap-4 transition-all group-hover:translate-x-1">
                                Detail
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination (jika menggunakan paginator Laravel) --}}
            {{-- @if(method_exists($reports, 'links') && $reports->hasPages())
            <div class="mt-12">
                {{ $reports->links() }}
            </div>
            @endif --}}
        @else
            {{-- Empty State dengan desain baru --}}
            <div
                class="bg-surface-container-low border border-slate-200 border-dashed rounded-lg p-12 flex flex-col items-center justify-center text-center space-y-4">
                <div class="p-6 rounded-full bg-white shadow-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl">description</span>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-on-surface">Belum ada laporan yang dikirim</h4>
                    <p class="text-on-surface-variant max-w-xs mx-auto mt-1">Silakan buat laporan baru melalui unit yang
                        tersedia.</p>
                </div>
                <a href="{{ route('student.units.index') }}"
                    class="bg-white border border-slate-200 px-8 py-3 rounded-full font-bold text-on-surface-variant hover:bg-slate-50 transition-colors">
                    Lihat Unit
                </a>
            </div>
        @endif
    </div>
@endsection