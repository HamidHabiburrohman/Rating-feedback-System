@extends('layouts.student.app')

@section('title', 'QR Scan Result')

@section('content')
<div class="py-6">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl text-green-600">✓</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">QR Code Valid</h1>
            <p class="text-sm text-gray-500 mb-6">{{ $unit->name }}</p>
            
            <div class="space-y-3">
                <a href="{{ route('student.ratings.create', $unit->slug) }}" class="block w-full py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700">Give Rating</a>
                <a href="{{ route('student.units.show', $unit->slug) }}" class="block w-full py-3 bg-white text-gray-700 font-medium border border-gray-300 rounded-md hover:bg-gray-50">View Unit Details</a>
            </div>
        </div>
    </div>
</div>
@endsection