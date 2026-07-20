<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            'settings.*' => 'nullable|string'
    ];
    }

    public function messages(): array
    {
        return [
            'settings.required' => 'Pengaturan wajib diisi',
            'settings.array' => 'Format pengaturan tidak valid'
    ];
    }
}