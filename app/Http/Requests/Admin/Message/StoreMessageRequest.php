<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Message;

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
            'employee_unit_assignment_id' => 'sometimes|exists:employee_unit_assignments,id',
            'message' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240',
            'message_type' => 'sometimes|string|in:text,image,file,system',
        ];
    }
}