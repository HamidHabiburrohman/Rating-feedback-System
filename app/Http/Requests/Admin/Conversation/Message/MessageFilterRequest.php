<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Message;

use Illuminate\Foundation\Http\FormRequest;

class MessageFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }
}