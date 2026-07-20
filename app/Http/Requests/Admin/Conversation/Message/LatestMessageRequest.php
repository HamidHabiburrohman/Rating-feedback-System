<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Message;

use Illuminate\Foundation\Http\FormRequest;

class LatestMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}