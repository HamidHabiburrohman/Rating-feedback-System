<?php

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
            'penerima_tipe' => 'required|in:admin,unit',
            'penerima_id' => 'required|integer',
            'unit_id' => 'nullable|exists:units,id',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'kategori' => 'required|in:technical,status_request,rating_feedback,maintenance,announcement,instruction,question,emergency',
            'prioritas' => 'required|in:biasa,penting,sangat_penting',
            'perlu_tindakan' => 'boolean',
            'tipe_tindakan' => 'nullable|string|max:100',
            'data_tindakan' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'penerima_tipe.required' => 'Tipe penerima harus diisi',
            'penerima_tipe.in' => 'Tipe penerima tidak valid',
            'penerima_id.required' => 'ID penerima harus diisi',
            'penerima_id.integer' => 'ID penerima harus berupa angka',
            'unit_id.exists' => 'Unit tidak ditemukan',
            'judul.required' => 'Judul pesan harus diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'pesan.required' => 'Isi pesan harus diisi',
            'kategori.required' => 'Kategori pesan harus dipilih',
            'kategori.in' => 'Kategori pesan tidak valid',
            'prioritas.required' => 'Prioritas pesan harus dipilih',
            'prioritas.in' => 'Prioritas pesan tidak valid',
            'perlu_tindakan.boolean' => 'Perlu tindakan harus berupa boolean',
            'tipe_tindakan.max' => 'Tipe tindakan maksimal 100 karakter',
            'data_tindakan.array' => 'Data tindakan harus berupa array',
        ];
    }

    public function prepareForValidation()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->merge([
                'pengirim_tipe' => 'admin',
                'pengirim_id' => $user->id,
                'status' => 'terkirim'
            ]);
        }

        if ($this->kategori === 'emergency') {
            $this->merge(['prioritas' => 'sangat_penting']);
        } elseif (in_array($this->kategori, ['technical', 'status_request'])) {
            $this->merge(['prioritas' => 'penting']);
        }

        if (in_array($this->kategori, ['status_request', 'technical', 'question'])) {
            $this->merge(['perlu_tindakan' => true]);
        }

        if ($this->kategori === 'status_request') {
            $this->merge(['tipe_tindakan' => 'update_unit_status']);
        } elseif ($this->kategori === 'technical') {
            $this->merge(['tipe_tindakan' => 'technical_support']);
        }
    }
}