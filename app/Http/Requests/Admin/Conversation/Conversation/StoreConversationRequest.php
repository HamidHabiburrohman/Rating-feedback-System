<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['nullable', 'string', 'max:255'],
            'participants' => ['required', 'array', 'min:1'],
            'participants.*' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'subject' => 'subject',
            'participants' => 'participants list',
        ];
    }
}