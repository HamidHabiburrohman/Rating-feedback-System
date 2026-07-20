<?php

namespace App\Http\Requests\Admin\ReportCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:report_categories,name',
            'slug' => 'required|string|max:255|unique:report_categories,slug|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
    ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Nama kategori sudah ada',
            'slug.required' => 'Slug wajib diisi',
            'slug.unique' => 'Slug sudah digunakan',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung'
    ];
    }
}