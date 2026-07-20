<?php

namespace App\Http\Requests\Admin\ModerationLog;

use Illuminate\Foundation\Http\FormRequest;

class ExportLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => 'sometimes|in:csv,excel,pdf',
            'action' => 'nullable|string',
            'admin_id' => 'nullable|exists:users,id',
            'target_type' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
    ];
    }
}