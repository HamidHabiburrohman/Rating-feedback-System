@props(['rating'])

<div
    class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/10 hover:shadow-md transition-shadow">
    <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs text-on-surface-variant">{{ $rating->tracking_code }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    @if($rating->status === 'active') bg-green-100 text-green-700
                    @elseif($rating->status === 'edited') bg-yellow-100 text-yellow-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($rating->status) }}
                </span>
            </div>
            <div class="flex items-center gap-1 mt-2">
                <div class="flex text-primary">
                    @php $score = round($rating->overall_score); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-sm {{ $i <= $score ? 'fill-icon' : '' }}"
                            style="font-variation-settings: 'FILL' {{ $i <= $score ? 1 : 0 }}">
                            star
                        </span>
                    @endfor
                </div>
                <span class="text-sm font-semibold ml-1">{{ number_format($rating->overall_score, 1) }}</span>
            </div>
        </div>
        <div class="text-right text-xs text-on-surface-variant">
            {{ $rating->created_at->diffForHumans() }}
        </div>
    </div>

    @if($rating->comment)
        <p class="text-on-surface text-sm mt-2 line-clamp-2">
            {{ Str::limit($rating->comment, 100) }}
        </p>
    @endif

    <div class="flex justify-end mt-3">
        <a href="{{ route('student.ratings.show', $rating->tracking_code) }}"
            class="text-primary text-sm font-medium hover:underline flex items-center gap-1">
            Lihat Detail
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
</div>