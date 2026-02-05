@extends('layouts.admin.app')

@section('title', 'Dashboard')

@push('admin-scripts')
    <!-- Load jQuery dan ApexCharts -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush

@section('admin-content')

    <body data-dashboard-overview="{{ route('admin.dashboard.overview') }}"
        data-dashboard-stats="{{ route('admin.dashboard.stats') }}"
        data-dashboard-charts="{{ route('admin.dashboard.charts') }}">
        <!-- Minimalist Stat Cards -->
       <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light-primary p-3 me-3">
                                <i class="ti ti-users text-primary fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">Today Visitors</h6>
                                <h4 class="fw-bold mb-0" id="visitors-today">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light-success p-3 me-3">
                                <i class="ti ti-star text-success fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">Today Ratings</h6>
                                <h4 class="fw-bold mb-0" id="ratings-today">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light-warning p-3 me-3">
                                <i class="ti ti-report-analytics text-warning fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">This Week Visitors</h6>
                                <h4 class="fw-bold mb-0" id="visitors-week">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light-info p-3 me-3">
                                <i class="ti ti-building text-info fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-semibold mb-1">Active Units</h6>
                                <h4 class="fw-bold mb-0" id="active-units">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 d-flex align-items-strech">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div class="">
                                <h5 class="card-title fw-semibold">Monthly Visitors</h5>
                            </div>
                            <div class="dropdown">
                                <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                    class="rounded-circle btn-transparent rounded-circle btn-sm px-1 btn shadow-none">
                                    <i class="ti ti-dots-vertical fs-7 d-block"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">Last 30 days</a></li>
                                    <li><a class="dropdown-item" href="#">Last 90 days</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                        </div>
                        <div id="profit"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-10 fw-semibold">Rating Distribution</h5>
                                <div class="row align-items-center">
                                    <div class="col-7">
                                        <h4 class="fw-semibold mb-3" id="avg-rating">4.5</h4>
                                        <div class="d-flex align-items-center mb-2">
                                            <span
                                                class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-arrow-up-left text-success"></i>
                                            </span>
                                            <p class="text-dark me-1 fs-3 mb-0">+2%</p>
                                            <p class="fs-3 mb-0">last month</p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                                <span class="fs-2">4-5 Stars</span>
                                            </div>
                                            <div>
                                                <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                                <span class="fs-2">3-4 Stars</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="d-flex justify-content-center">
                                            <div id="grade"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-8">
                                        <h5 class="card-title mb-10 fw-semibold">Reports Resolved</h5>
                                        <h4 class="fw-semibold mb-3" id="pending-reports">6</h4>
                                        <div class="d-flex align-items-center pb-1">
                                            <span
                                                class="me-2 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-arrow-up-right text-success"></i>
                                            </span>
                                            <p class="text-dark me-1 fs-3 mb-0">+12%</p>
                                            <p class="fs-3 mb-0">last month</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-end">
                                            <div
                                                class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-check fs-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="earning"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Units Table -->
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body p-4">
                        <div class="d-flex mb-4 justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Top Units Rate</h5>
                            <div class="dropdown">
                                <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                    class="rounded-circle btn-transparent rounded-circle btn-sm px-1 btn shadow-none">
                                    <i class="ti ti-dots-vertical fs-7 d-block"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#" onclick="loadFilteredUnits('popularity')">Most
                                            Popular</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="loadFilteredUnits('quality')">Highest
                                            Rated</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive" data-simplebar>
                            <table class="table table-borderless align-middle text-nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">Profile</th>
                                        <th scope="col">Average Rate</th>
                                        <th scope="col">Total Rate</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading top units...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </body>

@endsection