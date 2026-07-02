<?php

namespace App\Http\Requests\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unitId = $this->route('id') ?? $this->route('unit');

        return [
            'code' => "sometimes|string|max:50|unique:units,code,{$unitId}",
            'name' => 'sometimes|string|max:255',
            'unit_type_id' => 'sometimes|exists:unit_types,id',
            'unit_department_id' => 'sometimes|exists:unit_departments,id',
            'description' => 'nullable|string|max:2000',
            'location' => 'sometimes|string|max:255',
            'building' => 'nullable|string|max:100',
            'floor' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i|after:open_time',
            'capacity' => 'nullable|integer|min:1|max:9999',
            'is_active' => 'sometimes|boolean',
            'operational_status' => 'sometimes|in:open,full,maintenance,closed',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ];
    }
}