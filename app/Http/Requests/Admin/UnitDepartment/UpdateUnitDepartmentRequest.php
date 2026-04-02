<?php

namespace App\Http\Requests\Admin\UnitDepartment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('unit_department') ?? $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('unit_departments')->ignore($id),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('unit_departments')->ignore($id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('unit_departments')->ignore($id),
            ],
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}