<?php

namespace App\Http\Requests\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('unit');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units')->ignore($id),
            ],
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('units')->ignore($id),
            ],
            'unit_type_id' => 'required|exists:unit_types,id',
            'unit_department_id' => 'required|exists:unit_departments,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'building' => 'nullable|string|max:100',
            'floor' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i|after:open_time',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
            'operational_status' => 'required|in:open,full,maintenance,closed',
            'facilities' => 'sometimes|array',
            'facilities.*' => 'exists:facilities,id',
            'metadata' => 'nullable|array',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}