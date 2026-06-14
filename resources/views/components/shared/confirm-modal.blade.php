@props([
    'id' => 'confirm-modal',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'type' => 'danger'
])

@php
$confirmColors = [
    'danger' => 'bg-red-600 hover:bg-red-700',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700',
    'info' => 'bg-blue-600 hover:bg-blue-700',
];
$confirmColor = $confirmColors[$type] ?? $confirmColors['danger'];
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-600 mb-6">{{ $message }}</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    {{ $cancelText }}
                </button>
                <form id="{{ $id }}-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white {{ $confirmColor }} rounded-md">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>