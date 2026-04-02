@props(['unit'])

@if($unit->operatingHours && $unit->operatingHours->count())
<div class="section-block" style="margin-bottom: 14px;">
    <div class="section-header">
        <div class="section-icon" style="background:#fef3c7;">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="section-title">Jam Operasional</span>
    </div>

    <div class="section-body" style="padding-bottom: 14px;">
        @php
            $dayNames = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            $todayNum = now()->dayOfWeekIso; // 1=Mon … 7=Sun
        @endphp

        <div class="hours-list">
            @foreach($unit->operatingHours as $oh)
                @php $isToday = isset($oh->day_of_week) && $oh->day_of_week == $todayNum; @endphp

                <div class="hours-row">
                    <span class="hours-day {{ $isToday ? 'today' : '' }}">
                        {{ $dayNames[($oh->day_of_week ?? 1) - 1] ?? $oh->day }}

                        @if($isToday)
                            <span class="hours-today-badge">
                                <span class="hours-today-dot"></span>
                                Hari ini
                            </span>
                        @endif
                    </span>

                    @if($oh->is_closed ?? false)
                        <span class="hours-time closed">Tutup</span>
                    @else
                        <span class="hours-time">
                            {{ \Carbon\Carbon::parse($oh->open_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($oh->close_time)->format('H:i') }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif