<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->user('admin')->id;

        return [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($adminId)
            ],
            'phone' => 'nullable|string|max:25',
            'position' => 'nullable|string|max:120',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:150',
            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('admins')->ignore($adminId)
            ],
            'department' => 'nullable|string|max:100',
            'timezone' => 'nullable|string|max:60',
            'profile_banner' => 'nullable|string|max:255',
            'two_factor_enabled' => 'nullable|boolean'
    ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.max' => 'Nomor telepon maksimal 25 karakter.',
            'position.max' => 'Posisi maksimal 120 karakter.',
            'location.max' => 'Lokasi maksimal 150 karakter.',
            'employee_id.max' => 'ID Karyawan maksimal 50 karakter.',
            'employee_id.unique' => 'ID Karyawan sudah digunakan.',
            'department.max' => 'Departemen maksimal 100 karakter.',
            'timezone.max' => 'Timezone maksimal 60 karakter.',
            'profile_banner.max' => 'Banner profil maksimal 255 karakter.'
    ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('two_factor_enabled')) {
            $this->merge([
                'two_factor_enabled' => filter_var($this->two_factor_enabled, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}