<nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md shadow-[0_20px_40px_rgba(173,43,0,0.04)]">
    <div class="flex justify-between items-center w-full px-8 py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a href="{{ route('student.units.index') }}"
                class="text-2xl font-bold tracking-tighter text-slate-900">Itenas Portal</a>
            <div class="hidden md:flex gap-6 font-manrope text-sm font-semibold tracking-tight">
                <a href="{{ route('student.units.index') }}"
                    class="{{ request()->routeIs('student.units.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary hover:bg-orange-50 transition-all duration-300' }}">Units</a>
                <a href="{{ route('student.activities.index') }}"
                    class="text-on-surface-variant hover:text-primary transition-colors {{ request()->routeIs('student.activities.*') ? 'text-primary font-bold border-b-2 border-primary' : '' }}">
                    Activity
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            @auth('student')
                @php
                    $student = Auth::guard('student')->user();
                    $photoUrl = $student->photo_url ?? null;
                @endphp
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                        class="flex items-center gap-2 text-slate-700 font-semibold hover:text-primary transition-colors px-3 py-2 rounded-full hover:bg-orange-50">
                        @if($photoUrl && $photoUrl !== 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=ad2b00&color=fff&size=256')
                            <img src="{{ $photoUrl }}" alt="{{ $student->name }}"
                                class="w-8 h-8 rounded-full object-cover border border-primary/20">
                        @else
                            <span class="material-symbols-outlined text-primary">account_circle</span>
                        @endif
                        <span class="hidden md:inline">{{ $student->name }}</span>
                        <span class="material-symbols-outlined text-base">expand_more</span>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-[-10px]"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-[-10px]"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden"
                        style="display: none;">

                        <div class="py-2">
                            <a href="{{ route('student.profile.show') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-orange-50 transition-colors">
                                <span class="material-symbols-outlined text-primary text-base">account_circle</span>
                                My Profile
                            </a>
                            <a href="{{ route('student.ratings.history') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-orange-50 transition-colors">
                                <span class="material-symbols-outlined text-primary text-base">star</span>
                                My Ratings
                            </a>
                            <a href="{{ route('student.reports.history') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-orange-50 transition-colors">
                                <span class="material-symbols-outlined text-primary text-base">flag</span>
                                My Reports
                            </a>

                            <div class="border-t border-slate-200 my-1"></div>

                            <form method="POST" action="{{ route('student.auth.logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-outlined text-red-500 text-base">logout</span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('student.login') }}"
                    class="text-primary font-bold text-sm px-4 py-2 rounded-full hover:bg-orange-50 transition-all">
                    Login
                </a>
                <a href="{{ route('student.register') }}"
                    class="bg-linear-to-r from-primary to-primary-container text-white px-6 py-2 rounded-full font-bold text-sm hover:scale-105 active:scale-95 transition-transform">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="h-20"></div>