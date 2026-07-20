<?php

namespace App\Http\Requests\Admin\Rating;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:active,edited,archived'
    ];
    }
}