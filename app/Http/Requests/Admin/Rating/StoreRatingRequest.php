<?php

namespace App\Http\Requests\Admin\Rating;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'session_id' => 'required|string|max:100',
            'visitor_ip' => 'required|ip',
            'user_agent' => 'nullable|string|max:500',
            'komentar' => 'nullable|string|max:1000',
            'status' => 'in:pending,dibalas,selesai',
            'metadata' => 'nullable|array'
        ];
    }
}