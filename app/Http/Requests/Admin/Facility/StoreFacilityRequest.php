<?php

namespace App\Http\Requests\Admin\Facility;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:facilities,name',
            'icon_key' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean'
    ];
    }
}