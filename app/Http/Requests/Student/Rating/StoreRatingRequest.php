<?php

namespace App\Http\Requests\Student\Rating;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'scores' => 'required|array|min:1',
            'scores.*' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'unit_id.required' => 'Unit tidak ditemukan',
            'unit_id.exists' => 'Unit tidak valid',
            'scores.required' => 'Nilai rating harus diisi',
            'scores.*.required' => 'Setiap kategori harus diisi',
            'scores.*.integer' => 'Nilai harus berupa angka',
            'scores.*.min' => 'Nilai minimal adalah 1',
            'scores.*.max' => 'Nilai maksimal adalah 5',
            'comment.max' => 'Komentar maksimal 1000 karakter',
        ];
    }
}