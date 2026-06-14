@extends('layouts.admin.app')

@section('title', 'Create Employee')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="[
            'employees' => '#',
            'Create Employee' => null
        ]" />
        
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Employee</h1>
            
            <form method="POST" action="#" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500" required>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection