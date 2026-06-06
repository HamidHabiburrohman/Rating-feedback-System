<?php

namespace App\Http\Requests\Employee\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('employee')->check();
    }

    public function rules(): array
    {
        return [
            'reply' => 'required|string|min:3|max:5000',
            'is_public' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'reply.required' => 'Balasan wajib diisi',
            'reply.min' => 'Balasan minimal 3 karakter',
            'reply.max' => 'Balasan maksimal 5000 karakter',
        ];
    }
}