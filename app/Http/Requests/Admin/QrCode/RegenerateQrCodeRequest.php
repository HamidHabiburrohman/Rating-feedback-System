<?php

namespace App\Http\Requests\Admin\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class RegenerateQrCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'expires_at' => 'nullable|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'expires_at.after' => 'Tanggal kadaluarsa harus setelah hari ini',
        ];
    }
}