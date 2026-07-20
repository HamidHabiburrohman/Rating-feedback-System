<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Attachment;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMessageAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}