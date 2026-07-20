<?php

namespace App\Http\Requests\Admin\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'format' => 'sometimes|in:csv,excel,pdf,print',
            'type' => 'sometimes|string',
            'unit_id' => 'nullable|exists:units,id',
            'type_id' => 'nullable|exists:unit_types,id',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'search' => 'nullable|string|max:100',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'min_score' => 'nullable|numeric|min:1|max:5',
            'max_score' => 'nullable|numeric|min:1|max:5'
    ];
    }

    public function messages()
    {
        return [
            'format.in' => 'Format export tidak valid',
            'date_from.date' => 'Tanggal awal tidak valid',
            'date_to.date' => 'Tanggal akhir tidak valid',
            'date_to.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'min_score.numeric' => 'Nilai minimal harus angka',
            'max_score.numeric' => 'Nilai maksimal harus angka'
    ];
    }
}