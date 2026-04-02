<?php

namespace App\Http\Requests\Admin\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:new,in_progress,replied,resolved,rejected',
            'admin_response' => 'nullable|string|max:5000'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul laporan harus diisi',
            'title.max' => 'Judul laporan maksimal 255 karakter',
            'description.required' => 'Deskripsi laporan harus diisi',
            'priority.required' => 'Prioritas harus dipilih',
            'priority.in' => 'Prioritas tidak valid',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'admin_response.max' => 'Tanggapan maksimal 5000 karakter'
        ];
    }
}