<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'employee_id' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_id')->ignore($employeeId),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'position' => 'nullable|string|max:120',
            'department' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:25',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'timezone' => 'nullable|string|max:60',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah terdaftar',
            'employee_id.unique' => 'ID Karyawan sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }
}