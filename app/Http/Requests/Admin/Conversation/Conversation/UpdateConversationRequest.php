<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,archived,closed'],
        ];
    }
}