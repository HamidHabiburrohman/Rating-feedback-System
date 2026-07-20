<?php

namespace App\Http\Requests\Student\Rating;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
    ];
    }
}