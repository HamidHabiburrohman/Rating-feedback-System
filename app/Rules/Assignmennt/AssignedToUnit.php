<?php

namespace App\Rules\Employee;

use App\Models\Authentication\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AssignedToUnit implements ValidationRule
{
    public function __construct(
        protected int $unitId
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employee = Employee::find($value);

        if (!$employee || (int) $employee->unit_id !== $this->unitId) {
            $fail('Pegawai ini tidak ditugaskan pada unit layanan yang dipilih.');
        }
    }
}