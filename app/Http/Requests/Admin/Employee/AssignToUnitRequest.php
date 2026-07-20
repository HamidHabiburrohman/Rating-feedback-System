<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class AssignToUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('admin')->user();
        return $user && in_array($user->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'unit_id' => 'required|exists:units,id',
            'role_in_unit' => 'nullable|string|max:100',
            'assigned_at' => 'nullable|date',
            'ended_at' => 'nullable|date|after:assigned_at'
    ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Karyawan wajib dipilih',
            'employee_id.exists' => 'Karyawan tidak ditemukan',
            'unit_id.required' => 'Unit wajib dipilih',
            'unit_id.exists' => 'Unit tidak ditemukan',
            'ended_at.after' => 'Tanggal selesai harus setelah tanggal mulai'
    ];
    }
}