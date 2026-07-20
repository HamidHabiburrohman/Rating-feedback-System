<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Message;

use Illuminate\Foundation\Http\FormRequest;

class SearchMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'search' => 'search keyword',
        ];
    }
}