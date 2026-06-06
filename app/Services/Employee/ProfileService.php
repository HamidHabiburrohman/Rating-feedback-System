<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    protected Employee $employee;

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getProfile(): array
    {
        $this->employee->load('unitAssignments.unit');

        return [
            'employee' => $this->employee,
            'assignments' => $this->employee->unitAssignments()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                })
                ->with('unit')
                ->get(),
        ];
    }

    public function update(array $data): bool
    {
        $allowedFields = ['name', 'phone', 'photo', 'position', 'department', 'timezone', 'preferences'];

        $updateData = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateData)) {
            return false;
        }

        return $this->employee->update($updateData);
    }

    public function changePassword(string $oldPassword, string $newPassword): bool
    {
        if (!Hash::check($oldPassword, $this->employee->password)) {
            return false;
        }

        $this->employee->password = Hash::make($newPassword);
        return $this->employee->save();
    }
}