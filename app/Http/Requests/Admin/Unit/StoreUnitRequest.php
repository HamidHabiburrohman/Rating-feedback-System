<?php

namespace App\Http\Requests\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_unit' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:50|unique:units',
            'deskripsi' => 'nullable|string',
            'type_id' => 'required|exists:unit_types,id',
            'lokasi' => 'required|string|max:500',
            'gedung' => 'nullable|string|max:100',
            'lantai' => 'nullable|string|max:10',
            'kontak_telepon' => 'nullable|string|max:20',
            'kontak_email' => 'nullable|email|max:255',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'kapasitas' => 'nullable|integer|min:0',
            'status_aktif' => 'boolean',
            'status' => 'nullable|in:open,full,maintenance,closed',
            'foto_unit' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];
    }
}