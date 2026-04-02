<nav
    class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md shadow-[0_20px_40px_rgba(173,43,0,0.04)] tonal-transition">
    <div class="flex justify-between items-center w-full px-8 py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a href="{{ route('student.units.index') }}" class="text-2xl font-bold tracking-tighter text-slate-900">Itenas
                Portal</a>
            <div class="hidden md:flex gap-6 font-manrope text-sm font-semibold tracking-tight">
                <a href="{{ route('student.units.index') }}"
                    class="{{ request()->routeIs('student.units.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary hover:bg-orange-50 transition-all duration-300' }}">Units</a>
                <a href="#"
                    class="text-slate-600 hover:text-primary hover:bg-orange-50 transition-all duration-300">Research</a>
                <a href="#"
                    class="text-slate-600 hover:text-primary hover:bg-orange-50 transition-all duration-300">Faculty</a>
                <a href="#"
                    class="text-slate-600 hover:text-primary hover:bg-orange-50 transition-all duration-300">About</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-2 text-slate-700 font-semibold">
                        <span class="material-symbols-outlined">account_circle</span>
                        {{ Auth::user()->name }}
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-lg border border-outline-variant/10 hidden group-hover:block">
                        <a href="{{ route('student.profile.edit') }}"
                            class="block px-4 py-2 text-sm hover:bg-surface-container-low">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-surface-container-low">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('student.login') }}"
                    class="text-primary font-bold text-sm scale-95 active:scale-90 transition-transform">Login</a>
                    <a href="{{ route('home') }}" class="text-primary font-bold text-sm scale-95 active:scale-90 transition-transform">Home</a>
                {{-- <a href="{{ route('student.register') }}"
                    class="bg-linear-to-r from-primary to-primary-container text-white px-6 py-2 rounded-full font-bold text-sm scale-95 active:scale-90 transition-transform">Get
                    Started</a> --}}
            @endauth
        </div>
    </div>
</nav>
<!-- Spacer biar konten tidak ketutup navbar -->
<div class="h-24"></div>