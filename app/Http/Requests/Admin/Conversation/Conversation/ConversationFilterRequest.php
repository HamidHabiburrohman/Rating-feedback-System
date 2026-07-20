<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class ConversationFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,archived,closed'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'search' => 'search query',
            'status' => 'conversation status',
            'per_page' => 'items per page',
        ];
    }
}