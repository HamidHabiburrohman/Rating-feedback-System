{{-- resources/views/student/profile/partials/stats-card.blade.php --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-surface-container-low p-8 rounded-2xl flex items-center gap-6 group hover:bg-surface-container transition-colors duration-500">
        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-primary text-3xl">star_rate</span>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-on-surface">{{ $stats['total_ratings'] ?? 0 }}</div>
            <div class="text-sm font-semibold text-on-surface-variant">Total Ratings</div>
        </div>
    </div>
    <div class="bg-surface-container-low p-8 rounded-2xl flex items-center gap-6 group hover:bg-surface-container transition-colors duration-500">
        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-primary text-3xl">assignment_late</span>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-on-surface">{{ $stats['total_reports'] ?? 0 }}</div>
            <div class="text-sm font-semibold text-on-surface-variant">Total Reports</div>
        </div>
    </div>
    <div class="bg-surface-container-low p-8 rounded-2xl flex items-center gap-6 group hover:bg-surface-container transition-colors duration-500">
        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-on-surface">{{ $stats['member_since'] ?? '' }}</div>
            <div class="text-sm font-semibold text-on-surface-variant">Member Since</div>
        </div>
    </div>
</section>