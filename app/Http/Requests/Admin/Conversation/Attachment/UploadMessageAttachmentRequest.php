<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Attachment;

use Illuminate\Foundation\Http\FormRequest;

class UploadMessageAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attachments' => ['required', 'array', 'min:1', 'max:10'],
            'attachments.*' => ['required', 'file', 'mimes:pdf,docx,xlsx,png,jpg,jpeg,webp', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.*.max' => 'The :attribute may not be greater than 10MB.',
            'attachments.*.mimes' => 'The :attribute must be a file of type: pdf, docx, xlsx, png, jpg, jpeg, webp.',
        ];
    }

    public function attributes(): array
    {
        return [
            'attachments.*' => 'attachment',
        ];
    }
}