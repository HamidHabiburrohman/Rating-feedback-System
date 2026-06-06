<?php

namespace App\Http\Requests\Student\Notification;

use Illuminate\Foundation\Http\FormRequest;

class MarkAsReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('student')->user();
        $notificationId = $this->route('notification');

        if (!$user || !$notificationId) {
            return false;
        }

        return $user->notifications()->where('id', $notificationId)->exists();
    }

    public function rules(): array
    {
        return [];
    }
}