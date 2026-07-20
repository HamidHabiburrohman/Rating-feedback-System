<?php

namespace App\Http\Requests\Admin\Rating;

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
            'search' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'student_id' => 'nullable|exists:students,id',
            'status' => 'nullable|string|in:active,edited,archived',
            'min_score' => 'nullable|numeric|min:1|max:5',
            'max_score' => 'nullable|numeric|min:1|max:5|gte:min_score',
            'has_reports' => 'nullable|boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'sort' => 'nullable|string|in:id,created_at,overall_score',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|in:10,25,50,100'
    ];
    }
}