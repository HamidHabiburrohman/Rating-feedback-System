@extends('layouts.errors')
@section('code', '419')
@section('title', 'Sesi Berakhir')
@section('message', 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.')

@section('action')
    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-4">
        <a href="{{ route('student.login') }}" 
           class="px-6 py-3 bg-[#f8773c] text-white rounded-xl hover:bg-[#e0622a]">
            Login Student
        </a>
        <a href="{{ route('admin.login') }}" 
           class="px-6 py-3 border border-[#f8773c] text-[#f8773c] rounded-xl hover:bg-orange-50">
            Login Admin
        </a>
    </div>
@endsection