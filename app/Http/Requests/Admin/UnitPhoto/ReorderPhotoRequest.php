<?php

namespace App\Http\Requests\Admin\UnitPhoto;

use Illuminate\Foundation\Http\FormRequest;

class ReorderPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos' => 'required|array',
            'photos.*' => 'exists:unit_photos,id'
    ];
    }
}