<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Message;

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
            'message' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240',
        ];
    }
}