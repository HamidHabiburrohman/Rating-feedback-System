<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Attachment;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceMessageAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attachment' => ['required', 'file', 'mimes:pdf,docx,xlsx,png,jpg,jpeg,webp', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'attachment.max' => 'The attachment may not be greater than 10MB.',
            'attachment.mimes' => 'The attachment must be a file of type: pdf, docx, xlsx, png, jpg, jpeg, webp.',
        ];
    }
}