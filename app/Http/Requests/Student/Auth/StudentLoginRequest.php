<?php

namespace App\Http\Requests\Student\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_identifier' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'student_identifier.required' => 'NIM atau email wajib diisi',
            'student_identifier.max' => 'NIM maksimal 50 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }
}