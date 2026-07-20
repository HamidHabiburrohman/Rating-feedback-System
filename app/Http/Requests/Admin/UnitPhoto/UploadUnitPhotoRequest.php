<?php

namespace App\Http\Requests\Admin\UnitPhoto;

use Illuminate\Foundation\Http\FormRequest;

class UploadUnitPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'is_primary' => 'sometimes|boolean'
    ];
    }

    public function messages(): array
    {
        return [
            'photos.*.image' => 'File harus berupa gambar',
            'photos.*.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'photos.*.max' => 'Ukuran gambar maksimal 5MB'
    ];
    }
}