<?php

namespace App\Http\Requests\Admin\UnitPhoto;

use Illuminate\Foundation\Http\FormRequest;

class SetPrimaryPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo_id' => 'required|exists:unit_photos,id'
    ];
    }
}