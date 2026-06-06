<?php

namespace App\Http\Requests\Employee\Unit;

use Illuminate\Foundation\Http\FormRequest;

class ShowUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('employee')->user();
        $unitId = $this->route('unit');

        if (!$user || !$unitId) {
            return false;
        }

        return $user->unitAssignments()
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->exists();
    }

    public function rules(): array
    {
        return [];
    }
}