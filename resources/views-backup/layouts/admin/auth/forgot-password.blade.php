@extends('layouts.base.auth')

@section('title', 'Forgot Password')

@section('auth-content')
    <div class="auth-container">
        <div class="auth-card">
            @yield('forgot-content')
        </div>
    </div>
@endsection

@push('styles')
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 450px;
        padding: 2.5rem;
    }
</style>
@endpush