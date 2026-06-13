@props([
    'triggerId' => 'auth-modal',
    'loginRoute' => route('student.login'),
])

<div id="{{ $triggerId }}" class="auth-modal-overlay" onclick="closeAuthModal('{{ $triggerId }}')">
    <div class="auth-modal-card" onclick="event.stopPropagation()">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-primary-fixed/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-tertiary-fixed/20 rounded-full blur-3xl"></div>

        <button onclick="closeAuthModal('{{ $triggerId }}')"
            class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container-low transition-colors z-10">
            <span class="material-symbols-outlined text-on-surface-variant text-xl">close</span>
        </button>

        <div class="relative mb-6 md:mb-8">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-secondary-fixed rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-on-secondary-fixed-variant text-3xl md:text-4xl">rate_review</span>
            </div>
            <div class="absolute -bottom-1 -right-1 w-7 h-7 md:w-8 md:h-8 bg-primary-container rounded-full flex items-center justify-center border-4 border-surface-container-lowest">
                <span class="material-symbols-outlined text-on-primary-container text-xs md:text-sm" style="font-variation-settings: 'FILL' 1;">lock</span>
            </div>
        </div>

        <h2 class="text-2xl md:text-3xl font-bold text-on-surface tracking-tight mb-3 md:mb-4">
            {{ $title ?? 'Sign in to Rate This Unit' }}
        </h2>
        <p class="text-on-surface-variant text-sm md:text-base leading-relaxed mb-8 md:mb-10 px-2 md:px-4">
            {{ $description ?? 'You need to be logged in as a student to rate this unit and share your experience with the community.' }}
        </p>

        <div class="w-full space-y-3 md:space-y-4">
            <a href="{{ $loginRoute }}"
                class="block w-full py-3.5 md:py-4 px-6 md:px-8 bg-primary text-white rounded-full font-semibold text-base md:text-lg hover:shadow-lg hover:shadow-primary-container/20 active:scale-95 transition-all duration-300 text-center group">
                <span class="flex items-center justify-center gap-2">
                    Sign In to Continue
                    <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </span>
            </a>

            @if ($showSecondaryButton ?? true)
                <button onclick="closeAuthModal('{{ $triggerId }}')"
                    class="w-full py-2.5 md:py-3 px-6 md:px-8 text-on-surface-variant font-medium rounded-full hover:bg-surface-container-low active:scale-98 transition-all duration-200">
                    {{ $secondaryAction ?? 'Maybe Later' }}
                </button>
            @endif
        </div>

        <div class="mt-8 md:mt-10 flex items-center gap-2 opacity-40">
            <span class="text-xs font-bold tracking-widest uppercase">{{ $brandName ?? 'Itenas Portal' }}</span>
            <span class="w-1 h-1 bg-on-surface-variant rounded-full"></span>
            <span class="text-xs font-medium">{{ $brandTagline ?? 'Student Platform' }}</span>
        </div>
    </div>
</div>