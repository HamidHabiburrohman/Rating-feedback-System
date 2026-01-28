<?php

namespace App\Http\Requests\Admin\UnitType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $unitTypeId = $this->route('unit_type') ?? $this->route('id');
        
        return [
            'name' => 'required|string|max:100|unique:unit_types,name,' . $unitTypeId,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nama tipe unit',
            'description' => 'deskripsi',
            'is_active' => 'status aktif'
        ];
    }
}