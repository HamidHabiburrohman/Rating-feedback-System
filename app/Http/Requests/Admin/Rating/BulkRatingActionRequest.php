<?php

namespace App\Http\Requests\Admin\Rating;

use Illuminate\Foundation\Http\FormRequest;

class BulkRatingActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating_ids' => 'required|array',
            'rating_ids.*' => 'exists:ratings,id',
            'action' => 'required|string|in:archive,restore,delete'
    ];
    }
}