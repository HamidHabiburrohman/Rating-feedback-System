@extends('layouts.base')

@section('title', 'Itenas Unit - Admin Panel' . (isset($title) ? ' - ' . $title : ''))

@section('body-class', 'admin-layout')


@push('styles')
    <style>
        /* Custom Styles untuk Background Putih */
        .page-wrapper { 
            padding-top: 0 !important; 
            background-color: #fafafa !important;
        }
        
        .left-sidebar { 
            margin-top: 0 !important; 
            top: 0 !important; 
            height: 100vh !important; 
        }
        
        .body-wrapper { 
            margin-top: 0 !important; 
            background-color: #ffffff !important;
        }
        
        .body-wrapper-inner {
            background-color: #ffffff !important;
            min-height: 100vh;
        }
        
        .container-fluid {
            background-color: #ffffff !important;
            padding: 20px;
        }
        
        .body-wrapper, 
        .body-wrapper-inner,
        .container-fluid,
        #spa-content,
        .card {
            background-image: none !important;
        }
        
        .card {
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .app-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            margin-bottom: 1.5rem;
        }
        
        #spa-content {
            background-color: #ffffff !important;
            padding: 0;
            min-height: calc(100vh - 120px);
        }
        
        @media (max-width: 768px) {
            .body-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" 
         data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

        @include('layouts.admin.partials.sidebar')
        
        <div class="body-wrapper">
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    
                    @include('layouts.components.alert')
                    
                    <header class="app-header">
                        @include('layouts.admin.partials.navbar')
                    </header>

                    <div id="spa-content">
                        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

                        @yield('admin-content')
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <x-logout-modal />
@endsection

@push('scripts')
    @include('layouts.admin.partials.scripts')
    
    @stack('admin-scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elements = [
                document.body,
                document.querySelector('.page-wrapper'),
                document.querySelector('.body-wrapper'),
                document.querySelector('.body-wrapper-inner'),
                document.querySelector('.container-fluid'),
                document.getElementById('spa-content')
            ];

            elements.forEach(el => {
                if (el) {
                    el.style.backgroundColor = '#ffffff';
                }
            });
        });
    </script>
@endpush