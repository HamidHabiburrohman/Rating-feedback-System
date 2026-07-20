<?php

namespace App\Http\Requests\Student\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_identifier' => 'required|string|max:50|unique:students,student_identifier',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:8|confirmed',
            'major' => 'nullable|string|max:100',
            'class_year' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20'
    ];
    }

    public function messages(): array
    {
        return [
            'student_identifier.required' => 'NIM wajib diisi',
            'student_identifier.unique' => 'NIM sudah terdaftar',
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
    ];
    }
}