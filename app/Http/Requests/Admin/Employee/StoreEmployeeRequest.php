<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'nullable|string|max:120',
            'department' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:25',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'timezone' => 'nullable|string|max:60',
            'is_active' => 'sometimes|boolean'
    ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama karyawan wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'employee_id.required' => 'ID Karyawan wajib diisi',
            'employee_id.unique' => 'ID Karyawan sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
    ];
    }
}