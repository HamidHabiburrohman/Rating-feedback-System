<?php

namespace App\Http\Requests\Admin\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReplyReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggapan_admin' => 'required|string|max:250'
        ];
    }

    public function messages()
    {
        return [
            'tanggapan_admin.required' => 'Tanggapan harus diisi',
            'tanggapan_admin.max' => 'Tanggapan maksimal 250 karakter'
        ];
    }
}