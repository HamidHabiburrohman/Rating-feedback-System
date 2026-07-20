<?php

namespace App\Http\Requests\Employee\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('employee')->check();
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(['new', 'assigned', 'in_progress', 'replied', 'resolved', 'rejected', 'pending_preview']),
            ],
            'reason' => 'nullable|string|max:1000'
    ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid'
    ];
    }
}