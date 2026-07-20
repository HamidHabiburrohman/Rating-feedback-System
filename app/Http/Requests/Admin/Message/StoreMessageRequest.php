<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Message;

use App\Services\Admin\Conversation\MessageService;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['nullable', 'exists:conversations,id'],
            'body' => ['required_without:attachments', 'nullable', 'string', 'max:5000'],
            'subject' => ['required_without:conversation_id', 'nullable', 'string', 'max:255'],
            'participants' => ['required_without:conversation_id', 'nullable', 'array'],
            'participants.*' => ['integer'],
            'attachments' => ['nullable', 'array', 'max:' . MessageService::MAX_ATTACHMENTS],
            'attachments.*' => [
                'file',
                'max:' . (MessageService::MAX_FILE_SIZE / 1024), // dalam KB
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'body.max' => 'Message body cannot exceed ' . MessageService::MAX_BODY_LENGTH . ' characters.',
            'attachments.max' => 'Cannot upload more than ' . MessageService::MAX_ATTACHMENTS . ' files.',
            'attachments.*.max' => 'Each file cannot exceed ' . (MessageService::MAX_FILE_SIZE / 1024 / 1024) . 'MB.',
        ];
    }
}
