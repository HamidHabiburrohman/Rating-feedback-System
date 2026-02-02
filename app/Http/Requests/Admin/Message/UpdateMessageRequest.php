<?php

namespace App\Http\Requests\Admin\Message;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|in:terkirim,diterima,dibaca,ditanggapi,selesai',
            'perlu_tindakan' => 'boolean',
            'tipe_tindakan' => 'nullable|string|max:100',
            'data_tindakan' => 'nullable|array',
            'dibaca_pada' => 'nullable|date',
            'tindakan_diambil_pada' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status pesan tidak valid',
            'perlu_tindakan.boolean' => 'Perlu tindakan harus berupa boolean',
            'tipe_tindakan.max' => 'Tipe tindakan maksimal 100 karakter',
            'data_tindakan.array' => 'Data tindakan harus berupa array',
            'dibaca_pada.date' => 'Tanggal dibaca tidak valid',
            'tindakan_diambil_pada.date' => 'Tanggal tindakan tidak valid',
        ];
    }
}