{{-- resources/views/student/profile/partials/info-card.blade.php --}}
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