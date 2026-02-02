<?php

namespace App\Http\Requests\Admin\Rating;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'sometimes|exists:unihts,id',
            'komentar' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:pending,dibalas,selesai',
            'metadata' => 'nullable|array'
        ];
    }
}