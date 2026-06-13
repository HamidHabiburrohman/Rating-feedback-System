@extends('layouts.base.errors')
@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('message', 'Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.')

@section('action')
    <div class="mt-4">
        <form action="{{ route('home') }}" method="GET" class="inline-block">
            <input type="text" name="search" placeholder="Cari unit..."
                class="px-4 py-2 border rounded-l-lg focus:outline-none focus:border-[#f8773c]">
            <button type="submit" class="px-4 py-2 bg-[#f8773c] text-white rounded-r-lg hover:bg-[#e0622a]">
                Cari
            </button>
        </form>
    </div>
@endsection