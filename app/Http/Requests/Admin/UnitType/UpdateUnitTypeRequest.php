<?php

namespace App\Http\Requests\Admin\UnitType;

use App\Models\UnitType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeParam = $this->route('unit_type');

        if ($routeParam instanceof UnitType) {
            $id = $routeParam->getKey();
        } elseif (is_numeric($routeParam)) {
            $id = (int) $routeParam;
        } else {
            $segments = $this->segments();
            $id = (int) end($segments);
        }

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('unit_types', 'name')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('unit_types', 'slug')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'icon_key'    => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama tipe unit wajib diisi.',
            'name.unique'       => 'Nama tipe unit sudah digunakan.',
            'slug.unique'       => 'Slug sudah digunakan.',
            'icon_key.required' => 'Ikon wajib dipilih.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}