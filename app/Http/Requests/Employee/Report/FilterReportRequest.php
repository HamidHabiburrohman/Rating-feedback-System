<?php

namespace App\Http\Requests\Employee\Report;

use Illuminate\Foundation\Http\FormRequest;

class FilterReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Define your rules here
        ];
    }
}
