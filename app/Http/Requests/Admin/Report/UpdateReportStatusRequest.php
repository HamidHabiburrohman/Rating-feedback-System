<?php

namespace App\Http\Requests\Admin\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:in_progress,replied,resolved,rejected',
            'priority' => 'sometimes|string|in:low,medium,high,critical'
    ];
    }
}