<?php

namespace App\Http\Requests\Student\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class QrValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'qr_code' => 'required|string|exists:qr_codes,code',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }

    public function messages(): array
    {
        return [
            'qr_code.required' => 'QR Code wajib diisi',
            'qr_code.exists' => 'QR Code tidak valid',
            'latitude.required' => 'Lokasi tidak ditemukan',
            'latitude.numeric' => 'Format lokasi tidak valid',
            'latitude.between' => 'Lokasi tidak valid',
            'longitude.required' => 'Lokasi tidak ditemukan',
            'longitude.numeric' => 'Format lokasi tidak valid',
            'longitude.between' => 'Lokasi tidak valid',
        ];
    }
}