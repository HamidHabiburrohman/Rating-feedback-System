<?php

namespace App\Http\Requests\Admin\ModerationLog;

use Illuminate\Foundation\Http\FormRequest;

class FilterModerationLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'action' => 'nullable|string',
            'admin_id' => 'nullable|exists:users,id',
            'target_type' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'sort' => 'nullable|string|in:id,created_at,action',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ];
    }
}