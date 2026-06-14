@extends('layouts.student.app')

@section('title', 'Email Verified')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl text-green-600">✓</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Email Verified!</h1>
        <p class="text-gray-600 mb-6">Your email has been successfully verified. You can now login to your account.</p>
        <a href="{{ route('student.login') }}" class="inline-block w-full py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700">Login Now</a>
    </div>
</div>
@endsection