<?php
// app/Http/Requests/Student/Profile/UpdateProfileRequest.php

namespace App\Http\Requests\Student\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->user('student');
        $internalId = $student ? $student->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($internalId, 'id'),
            ],
            'major' => 'nullable|string|max:100',
            'class_year' => 'nullable|string|max:10',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:100',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh akun lain',
            'portfolio_url.url' => 'Format URL portfolio tidak valid',
            'linkedin_url.url' => 'Format URL LinkedIn tidak valid',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}