@props(['report'])

<div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/10 hover:shadow-md transition-shadow">
    <div class="flex flex-wrap justify-between items-start gap-3">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    @if($report->status === 'new') bg-blue-100 text-blue-700
                    @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-700
                    @elseif($report->status === 'replied') bg-purple-100 text-purple-700
                    @elseif($report->status === 'resolved') bg-green-100 text-green-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ $report->status_label }}
                </span>
                <span class="text-xs text-on-surface-variant font-mono">{{ $report->tracking_code }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    @if($report->priority === 'critical') bg-red-100 text-red-700
                    @elseif($report->priority === 'high') bg-orange-100 text-orange-700
                    @elseif($report->priority === 'medium') bg-yellow-100 text-yellow-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $report->priority_label }}
                </span>
            </div>
            <h3 class="font-bold text-on-surface">{{ $report->title }}</h3>
            <p class="text-sm text-on-surface-variant mt-1 line-clamp-2">{{ $report->description }}</p>
            <div class="flex items-center gap-3 mt-2 text-xs text-on-surface-variant">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">apartment</span>
                    {{ $report->unit->name }}
                </span>
                <span>{{ $report->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div>
            <a href="{{ route('student.reports.show', $report->tracking_code) }}" 
               class="text-primary text-sm font-medium hover:underline flex items-center gap-1">
                Detail
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</div>