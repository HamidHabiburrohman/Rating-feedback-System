<?php

namespace App\Services\Admin;

use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function getEmployees(array $filters = []): LengthAwarePaginator
    {
        $query = Employee::with('unit');
        
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        
        if (!empty($filters['status'])) {
            $statusFilters = explode(',', $filters['status']);
            $query->whereIn('status', $statusFilters);
        }
        
        if (!empty($filters['unit'])) {
            $unitFilters = explode(',', $filters['unit']);
            $query->whereIn('unit_id', $unitFilters);
        }
        
        if (!empty($filters['bidang'])) {
            $bidangFilters = explode(',', $filters['bidang']);
            $query->whereIn('bidang', $bidangFilters);
        }
        
        $sort = $filters['sort'] ?? 'nama';
        $order = $filters['order'] ?? 'asc';
        $query->orderBy($sort, $order);
        
        $perPage = $filters['per_page'] ?? 10;
        
        return $query->paginate($perPage);
    }

    public function createEmployee(array $data): Employee
    {
        return Employee::create($data);
    }

    public function getEmployeeDetail(string $id): Employee
    {
        return Employee::with('unit')->findOrFail($id);
    }

    public function updateEmployee(string $id, array $data): Employee
    {
        $employee = Employee::findOrFail($id);
        $employee->update($data);
        return $employee->fresh();
    }

    public function deleteEmployee(string $id): void
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
    }

    public function updateEmployeeStatus(string $id, string $status): Employee
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['status' => $status]);
        return $employee->fresh();
    }
}