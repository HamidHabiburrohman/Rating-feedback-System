<?php

namespace App\Http\Requests\Admin\UnitDepartment;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:unit_departments,name',
            'slug' => 'nullable|string|max:255|unique:unit_departments,slug',
            'code' => 'nullable|string|max:50|unique:unit_departments,code',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
    ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}