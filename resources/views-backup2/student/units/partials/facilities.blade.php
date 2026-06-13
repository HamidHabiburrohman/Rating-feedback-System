@if($unit->facilities->count())
    <div class="space-y-6">
        <h3 class="text-2xl font-bold text-on-surface tracking-tight">Available Facilities</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($unit->facilities as $facility)
                <div
                    class="flex items-start space-x-4 p-6 rounded-xl bg-surface-container-low hover:bg-surface-container-lowest transition-all duration-300 group border border-outline-variant/10">
                    <div
                        class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed-variant group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl">{{ $facility->icon_key ?? '' }}</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-on-surface text-lg mb-1">{{ $facility->name }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif