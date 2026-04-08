<?php

namespace App\Http\Requests\Student\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ];
    }
}