<?php

namespace App\Http\Requests\Student\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating_id' => 'required|exists:ratings,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ];
    }
}