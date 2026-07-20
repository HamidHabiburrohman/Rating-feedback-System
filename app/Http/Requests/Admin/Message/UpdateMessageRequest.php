<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Message;

use App\Services\Admin\Conversation\MessageService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:' . MessageService::MAX_BODY_LENGTH],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Message body is required.',
            'body.max' => 'Message body cannot exceed ' . MessageService::MAX_BODY_LENGTH . ' characters.',
        ];
    }
}