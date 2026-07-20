<?php

namespace App\Http\Requests\Admin\Rating;

use Illuminate\Foundation\Http\FormRequest;

class ModerateRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => 'required|string|in:censor,archive,restore',
            'reason' => 'required_if:action,censor|nullable|string|max:500'
    ];
    }
}