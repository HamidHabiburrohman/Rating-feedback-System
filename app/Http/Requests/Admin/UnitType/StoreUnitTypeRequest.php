<?php

namespace App\Http\Requests\Admin\UnitType;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:unit_types,name',
            'slug' => 'nullable|string|max:255|unique:unit_types,slug',
            'icon_key' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
    ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}