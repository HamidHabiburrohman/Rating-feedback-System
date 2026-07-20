<?php

namespace App\Http\Requests\Admin\Report;

use Illuminate\Foundation\Http\FormRequest;

class BulkReportActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
            'action' => 'required|string|in:in_progress,replied,resolved,rejected'
    ];
    }
}