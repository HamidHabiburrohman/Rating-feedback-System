@extends('layouts.admin.app')

@section('title', 'Admins')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="['Admins' => route('admin.dashboard')]" />
        
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Admins</h1>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md hover:bg-orange-700">
                + Add New admin
            </a>
        </div>

        <x-shared.data-table :headers="['ID', 'Name', 'Status', 'Actions']">
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    <x-shared.empty-state message="No admin data available" />
                </td>
            </tr>
        </x-shared.data-table>
    </div>
</div>
@endsection