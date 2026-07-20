<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Message;

use App\Models\Conversation\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'body' => ['nullable', 'string', 'max:5000'],
            'type' => ['nullable', 'string', Rule::in([
                Message::TYPE_TEXT,
                Message::TYPE_SYSTEM,
                Message::TYPE_ATTACHMENT,
            ])],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'mimes:pdf,docx,xlsx,png,jpg,jpeg,webp', 'max:10240'],
            'metadata' => ['nullable', 'array'],
            'subject' => ['required_without:conversation_id', 'nullable', 'string', 'max:255'],
            'participants' => ['required_without:conversation_id', 'nullable', 'array', 'min:1'],
            'participants.*' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'body' => 'message body',
            'type' => 'message type',
            'attachments.*' => 'attachment',
            'subject' => 'conversation subject',
            'participants' => 'participants list',
        ];
    }
}