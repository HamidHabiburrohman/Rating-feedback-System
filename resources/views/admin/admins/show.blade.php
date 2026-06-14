@extends('layouts.admin.app')

@section('title', 'Admin Detail')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="[
            'admins' => '#',
            'Detail' => null
        ]" />
        
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Admin Detail</h1>
            
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">-</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-shared.status-badge status="active" /></dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection