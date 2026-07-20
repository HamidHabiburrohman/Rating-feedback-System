<?php

namespace App\Http\Requests\Admin\ReportCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        $categoryId = $this->route('reportCategory');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('report_categories', 'name')->ignore($categoryId),
            ],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('report_categories', 'slug')->ignore($categoryId),
            ],
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
    ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama kategori sudah ada',
            'slug.unique' => 'Slug sudah digunakan',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung'
    ];
    }
}