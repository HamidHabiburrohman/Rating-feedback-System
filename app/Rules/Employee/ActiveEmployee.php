<?php

namespace App\Rules\Employee;

use Closure;
use App\Models\Authentication\Employee;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveEmployee implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employee = Employee::find($value);

        if (!$employee) {
            $fail('Pegawai tidak ditemukan.');
            return;
        }

        if (!$employee->is_active || $employee->role !== 'employee') {
            $fail('Akun pegawai tidak aktif atau bukan pegawai resmi.');
        }
    }
}