@extends('layouts.errors')

@section('code', '403')
@section('title', 'Akses Ditolak')
@section('message', 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.')

@section('action')
    @if(Auth::check())
        <a href="{{ route('admin.dashboard') }}" class="text-[#f8773c] hover:underline">
            Kembali ke Dashboard
        </a>
    @else
        <a href="{{ route('admin.login') }}" class="text-[#f8773c] hover:underline">
            Login sebagai Admin
        </a>
    @endif
@endsection