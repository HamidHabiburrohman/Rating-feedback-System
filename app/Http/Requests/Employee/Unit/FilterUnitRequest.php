<?php

namespace App\Http\Requests\Employee\Unit;

use Illuminate\Foundation\Http\FormRequest;

class FilterUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'per_page' => 'nullable|integer|in:10,25,50,100'
    ];
    }
}
