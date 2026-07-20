<?php

namespace App\Http\Requests\Student\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'rating_id' => 'required|exists:ratings,id',
            'category' => 'required|in:technical,facility,network,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
    ];
    }

    public function messages(): array
    {
        return [
            'rating_id.required' => 'Rating tidak ditemukan',
            'rating_id.exists' => 'Rating tidak valid',
            'category.required' => 'Kategori harus dipilih',
            'category.in' => 'Kategori tidak valid',
            'title.required' => 'Judul laporan harus diisi',
            'title.max' => 'Judul maksimal 255 karakter',
            'description.required' => 'Deskripsi laporan harus diisi',
            'description.max' => 'Deskripsi maksimal 5000 karakter',
            'attachment.file' => 'File tidak valid',
            'attachment.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF',
            'attachment.max' => 'Ukuran file maksimal 10MB'
    ];
    }
}