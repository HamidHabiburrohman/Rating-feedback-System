@extends('layouts.student.app')

@section('title', 'Detail Laporan - ' . $report->tracking_code)

@section('content')
    <div class="min-h-screen pt-32 pb-20 px-6 max-w-3xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('student.reports.history') }}"
                class="text-on-surface-variant hover:text-primary flex items-center gap-1 text-sm">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kembali ke Riwayat
            </a>
        </div>

        <!-- Header -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10 mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                @if($report->status === 'new') bg-blue-100 text-blue-700
                                @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-700
                                @elseif($report->status === 'replied') bg-purple-100 text-purple-700
                                @elseif($report->status === 'resolved') bg-green-100 text-green-700
                                @else bg-gray-100 text-gray-700 @endif">
                            {{ $report->status_label }}
                        </span>
                        <span class="text-xs text-on-surface-variant font-mono">{{ $report->tracking_code }}</span>
                    </div>
                    <h1 class="text-xl md:text-2xl font-bold text-on-surface">{{ $report->title }}</h1>
                    <div class="flex items-center gap-3 mt-2 text-sm">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">apartment</span>
                            {{ $report->unit->name }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-outline"></span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">flag</span>
                            Prioritas: {{ $report->priority_label }}
                        </span>
                    </div>
                </div>
                <div class="text-right text-xs text-on-surface-variant">
                    Dilaporkan: {{ $report->created_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Deskripsi Laporan -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10 mb-6">
            <h2 class="text-lg font-bold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined">description</span>
                Deskripsi Laporan
            </h2>
            <p class="text-on-surface leading-relaxed whitespace-pre-line">{{ $report->description }}</p>
        </div>

        <!-- Respon Admin -->
        @if($report->admin_response)
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 border border-outline-variant/10 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-primary">support_agent</span>
                    <span class="font-bold text-primary">Tanggapan Admin</span>
                    <span class="text-xs text-on-surface-variant">{{ $report->replied_at?->format('d M Y H:i') }}</span>
                </div>
                <p class="text-on-surface">{{ $report->admin_response }}</p>
            </div>
        @endif

        <!-- Status Timeline -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10">
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">timeline</span>
                Status Laporan
            </h2>
            <div class="relative">
                @php
                    $allStatuses = [
                        'new' => 'Laporan Diterima',
                        'in_progress' => 'Sedang Diproses',
                        'replied' => 'Ditanggapi',
                        'resolved' => 'Selesai',
                    ];

                    // Tambahkan status 'rejected' hanya jika status saat ini adalah rejected
                    if ($report->status === 'rejected') {
                        $allStatuses['rejected'] = 'Ditolak';
                    }

                    $statuses = $allStatuses;
                    $currentIndex = array_search($report->status, array_keys($statuses));
                @endphp

                <div class="flex justify-between">
                    @foreach($statuses as $key => $label)
                            <div class="text-center flex-1">
                                <div
                                    class="w-8 h-8 rounded-full mx-auto flex items-center justify-center
                        {{ array_search($key, array_keys($statuses)) <= $currentIndex ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant' }}">
                                    @php
                                        $statusIndex = array_search($key, array_keys($statuses));
                                        $isRejectedOrResolved = ($key === 'resolved' || $key === 'rejected');
                                    @endphp

                                    @if($statusIndex < $currentIndex || ($statusIndex == $currentIndex && $isRejectedOrResolved))
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    @else
                                        <span class="text-xs">{{ $statusIndex + 1 }}</span>
                                    @endif
                                </div>
                                <div
                                    class="text-xs mt-2 font-medium {{ $statusIndex <= $currentIndex ? 'text-primary' : 'text-on-surface-variant' }}">
                                    {{ $label }}
                                </div>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-px bg-outline-variant self-start mt-4"></div>
                            @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection