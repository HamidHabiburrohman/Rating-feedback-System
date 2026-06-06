<?php

namespace App\Http\Requests\Employee\Rating;

use Illuminate\Foundation\Http\FormRequest;

class FilterRatingRequest extends FormRequest
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
