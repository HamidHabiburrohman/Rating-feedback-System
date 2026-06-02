<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => 'nullable|in:light,dark,system',
            'language' => 'nullable|in:id,en',
            'notifications' => 'nullable|boolean',
            'compact_sidebar' => 'nullable|boolean',
            'show_activity' => 'nullable|boolean',
            'login_notifications' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'theme.in' => 'Tema tidak valid.',
            'language.in' => 'Bahasa tidak valid.',
        ];
    }

    protected function prepareForValidation()
    {
        $booleanFields = ['notifications', 'compact_sidebar', 'show_activity', 'login_notifications'];
        
        foreach ($booleanFields as $field) {
            if ($this->has($field)) {
                $value = $this->input($field);
                $this->merge([
                    $field => filter_var($value, FILTER_VALIDATE_BOOLEAN)
                ]);
            }
        }
    }
}