@extends('layouts.student.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="py-6">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 text-center">Scan QR Code</h1>
            <p class="text-sm text-gray-500 text-center mb-6">Scan the QR code at the unit location</p>
            
            <div class="aspect-square bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                <div id="qr-reader" class="w-full h-full"></div>
            </div>
            
            <form method="POST" action="{{ route('student.qr.validate') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Or enter code manually</label>
                    <input type="text" name="code" placeholder="e.g., UNIT-ABC12345" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <button type="submit" class="w-full py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700">Validate</button>
            </form>
        </div>
    </div>
</div>
@endsection