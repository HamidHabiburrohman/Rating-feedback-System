@extends('layouts.errors')

@section('code', '500')
@section('title', 'Terjadi Kesalahan')
@section('message', 'Maaf, terjadi kesalahan pada server. Tim teknis kami sedang menanganinya.')

@section('action')
    <button onclick="location.reload()" 
            class="mt-4 px-6 py-3 border border-[#ddd] text-[#555] rounded-xl hover:bg-white transition-all">
        Coba Lagi
    </button>
@endsection