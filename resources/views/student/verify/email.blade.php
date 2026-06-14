@extends('layouts.student.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl">📧</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Verify Your Email</h1>
        <p class="text-gray-600 mb-6">We've sent a verification link to your email address. Please check your inbox and click the link to activate your account.</p>
        
        <div class="bg-gray-50 rounded-md p-4 mb-6">
            <p class="text-sm text-gray-500">Didn't receive the email?</p>
            <form method="POST" action="{{ route('student.verify.resend') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-orange-600 font-medium hover:text-orange-700">Resend verification email</button>
            </form>
        </div>
        
        <a href="{{ route('student.login') }}" class="text-sm text-gray-500 hover:text-gray-700">Back to login</a>
    </div>
</div>
@endsection