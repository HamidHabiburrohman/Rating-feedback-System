@extends('layouts.student.app')

@section('title', 'Student Portal')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-linear-to-br from-orange-50 to-white">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 m-4">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-linear-to-br from-[#f8773c] to-[#e0622a] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-bold text-2xl">S</span>
            </div>
            <h1 class="text-2xl font-bold text-[#1A1A1A]">Student Portal</h1>
            <p class="text-[#555] text-sm mt-2">Unit Feedback System</p>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('student.login') }}" 
               class="block w-full py-3 px-4 bg-linear-to-r from-[#f8773c] to-[#e0622a] text-white text-center font-semibold rounded-xl hover:shadow-lg transition-all">
                Login Mahasiswa
            </a>
            <a href="{{ route('visitor.browse') }}" 
               class="block w-full py-3 px-4 border border-gray-200 text-[#555] text-center font-semibold rounded-xl hover:bg-gray-50 transition-all">
                Browse Units (Tanpa Login)
            </a>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-xs text-[#999]">
                &copy; {{ date('Y') }} Unit Feedback System
            </p>
        </div>
    </div>
</div>
@endsection