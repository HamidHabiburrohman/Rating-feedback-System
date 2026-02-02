<?php

namespace App\Http\Requests\Admin;

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
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tipe' => 'required|in:masalah,saran,keluhan,pujian,lainnya',
            'prioritas' => 'required|in:rendah,sedang,tinggi,kritis',
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'unit_id' => 'nullable|exists:units,id',
            'lampiran' => 'nullable|array'
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul laporan harus diisi.',
            'deskripsi.required' => 'Deskripsi laporan harus diisi.',
            'tipe.required' => 'Tipe laporan harus dipilih.',
            'prioritas.required' => 'Prioritas laporan harus dipilih.',
            'status.required' => 'Status laporan harus dipilih.'
        ];
    }
}