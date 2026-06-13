@extends('layouts.student.app')

@section('title', 'Units | Itenas Portal')

@section('content')
    <header class="pt-12 pb-20 px-8 flex flex-col items-center justify-center text-center bg-surface-container-low">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-6xl md:text-7xl font-extrabold tracking-tighter text-on-surface mb-8">Find Your <span
                    class="text-primary italic">Unit</span></h1>
            <p class="text-on-surface-variant text-lg mb-12 max-w-xl mx-auto">Access world-class facilities, specialized
                laboratories, and collaborative workspaces across the Itenas ecosystem.</p>

            <form action="{{ route('student.units.index') }}" method="GET" class="relative w-full max-w-2xl mx-auto group"
                id="searchForm">
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

    <div id="filterContainer">
        @include('student.units.partials.filters', ['unitTypes' => $unitTypes])
    </div>

    <main class="max-w-7xl mx-auto px-8 pb-32">
        <div id="loadingOverlay"
            class="fixed inset-0 bg-surface/50 backdrop-blur-sm z-50 hidden items-center justify-center">
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-2xl flex flex-col items-center gap-4">
                <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <p class="text-on-surface-variant font-medium">Loading units...</p>
            </div>
        </div>

        <div id="unitsGrid">
            @include('student.units.partials.unit-grid', ['units' => $units])
        </div>

        <div class="mt-16" id="paginationContainer">
            {{ $units->links('layouts.student.partials.pagination') }}
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        const SkeletonLoader = {
            gridContainer: document.getElementById('unitsGrid'),
            gridElement: null,
            originalContent: null,

            showSkeletons(count = 6) {
                this.gridElement = document.getElementById('unitsGridInner');
                if (!this.gridElement) return;

                this.originalContent = this.gridContainer.innerHTML;

                let skeletons = '';
                for (let i = 0; i < count; i++) {
                    skeletons += `
                                <div class="bg-surface-container-lowest rounded-lg p-4 animate-pulse">
                                    <div class="relative h-64 w-full mb-6 overflow-hidden rounded-xl bg-surface-container-highest"></div>
                                    <div class="px-2">
                                        <div class="h-8 bg-surface-container-highest rounded-lg mb-3 w-3/4"></div>
                                        <div class="flex flex-col gap-2 mb-8">
                                            <div class="flex items-center gap-2">
                                                <div class="w-4 h-4 bg-surface-container-highest rounded-full"></div>
                                                <div class="h-4 bg-surface-container-highest rounded w-32"></div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-4 h-4 bg-surface-container-highest rounded-full"></div>
                                                <div class="h-4 bg-surface-container-highest rounded w-40"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t border-outline-variant/10">
                                            <div class="flex items-center gap-1">
                                                <div class="w-5 h-5 bg-surface-container-highest rounded-full"></div>
                                                <div class="h-5 bg-surface-container-highest rounded w-12"></div>
                                            </div>
                                            <div class="h-9 bg-surface-container-highest rounded-full w-20"></div>
                                        </div>
                                    </div>
                                </div>
                            `;
                }
                this.gridContainer.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="unitsGridInner">${skeletons}</div>`;
            },

            hideSkeletons() {
                if (this.gridContainer && this.originalContent) {
                    this.gridContainer.innerHTML = this.originalContent;
                }
            },

            restoreAndUpdate(newContent) {
                if (this.gridContainer) {
                    this.gridContainer.innerHTML = newContent;
                    this.originalContent = newContent;
                }
            }
        };

        async function loadUnits(url) {
            SkeletonLoader.showSkeletons(6);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const text = await response.text();
                    console.error('Response error:', text);
                    throw new Error('Network error: ' + response.status);
                }

                const data = await response.json();

                if (data.grid) {
                    const unitsGrid = document.getElementById('unitsGrid');
                    if (unitsGrid) {
                        unitsGrid.innerHTML = data.grid;
                        SkeletonLoader.gridElement = document.getElementById('unitsGridInner');
                        SkeletonLoader.originalContent = data.grid;
                    }
                }

                if (data.pagination) {
                    const paginationContainer = document.querySelector('#paginationContainer');
                    if (paginationContainer) {
                        paginationContainer.innerHTML = data.pagination;
                    }
                }

                window.history.pushState({}, '', url);

                updateActiveFilterButton(url);

            } catch (error) {
                console.error('Loading error:', error);
                SkeletonLoader.hideSkeletons();
                if (typeof Toast !== 'undefined') {
                    Toast.show('Failed to load units. Please try again.');
                }
            }
        }

        function updateActiveFilterButton(url) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const activeType = urlParams.get('type');

            document.querySelectorAll('.filter-btn').forEach(btn => {
                const btnType = btn.dataset.type;

                btn.classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/20');
                btn.classList.add('bg-surface-container-lowest', 'text-on-surface-variant');

                if ((!activeType && btnType === 'all') || (activeType && btnType === activeType)) {
                    btn.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant');
                    btn.classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/20');
                }
            });
        }

        function initFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const searchForm = document.getElementById('searchForm');

            console.log('Filter buttons found:', filterButtons.length);

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const url = this.dataset.filterUrl;
                    console.log('Filter clicked, URL:', url);
                    if (url) {
                        loadUnits(url);
                    }
                });
            });

            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const params = new URLSearchParams(formData);
                    const url = `${this.action}?${params.toString()}`;
                    console.log('Search submitted, URL:', url);
                    loadUnits(url);
                });
            }
        }

        window.addEventListener('popstate', function () {
            loadUnits(window.location.href);
        });

        document.addEventListener('DOMContentLoaded', function () {
            initFilters();
        });
    </script>
@endpush