<?php

namespace App\Http\Requests\Student\Rating;

use Illuminate\Foundation\Http\FormRequest;

class RatingFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:active,edited,archived',
            'sort' => 'nullable|string|in:created_at,overall_score,updated_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}