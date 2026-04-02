@extends('layouts.student.app')

@section('title', 'Units | Itenas Portal')

@section('content')
    <!-- Hero Section -->
    <header class="pt-12 pb-20 px-8 flex flex-col items-center justify-center text-center bg-surface-container-low">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-6xl md:text-7xl font-extrabold tracking-tighter text-on-surface mb-8">Find Your <span
                    class="text-primary italic">Unit</span></h1>
            <p class="text-on-surface-variant text-lg mb-12 max-w-xl mx-auto">Access world-class facilities, specialized
                laboratories, and collaborative workspaces across the Itenas ecosystem.</p>

            <form action="{{ route('student.units.index') }}" method="GET" class="relative w-full max-w-2xl mx-auto group">
                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline">search</span>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full bg-surface-container-lowest border-none shadow-[0_20px_40px_rgba(173,43,0,0.06)] h-16 pl-16 pr-36 rounded-full text-lg focus:ring-2 focus:ring-primary/20 transition-all duration-300 placeholder:text-outline-variant"
                    placeholder="Search by name, faculty, or equipment...">
                <button type="submit"
                    class="absolute right-3 top-3 bottom-3 bg-primary text-white px-8 rounded-full font-bold text-sm hover:bg-primary-container transition-colors">Search</button>
            </form>
        </div>
    </header>

    <!-- Filter Pills -->
    @include('student.units.partials.filters', ['categories' => $categories ?? [], 'activeCategory' => $activeCategory ?? ''])

    <!-- Unit Grid -->
    <main class="max-w-7xl mx-auto px-8 pb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @forelse($units as $unit)
                @include('student.units.partials.unit-grid', ['unit' => $unit])
            @empty
                <div class="col-span-full text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-outline">search_off</span>
                    <p class="text-on-surface-variant mt-4">No units found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $units->links('layouts.student.partials.pagination') }}
        </div>
    </main>
@endsection