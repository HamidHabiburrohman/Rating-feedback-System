<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Conversation\Participant;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_type' => ['required', 'string', Rule::in([
                Admin::class,
                Employee::class,
                Student::class,
            ])],
            'participant_id' => ['required', 'integer', 'exists:' . $this->getParticipantTable() . ',id'],
        ];
    }

    protected function getParticipantTable(): string
    {
        return match ($this->input('participant_type')) {
            Admin::class => 'admins',
            Employee::class => 'employees',
            Student::class => 'students',
            default => 'admins',
        };
    }

    public function attributes(): array
    {
        return [
            'participant_type' => 'participant type',
            'participant_id' => 'participant ID',
        ];
    }
}