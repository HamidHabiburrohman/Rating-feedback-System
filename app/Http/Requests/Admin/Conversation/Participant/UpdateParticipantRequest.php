<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Participant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => ['nullable', 'string', 'max:50'],
            'permission' => ['nullable', 'string', 'max:50'],
        ];
    }
}