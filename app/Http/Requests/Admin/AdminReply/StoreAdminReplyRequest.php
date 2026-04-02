<?php

namespace App\Http\Requests\Admin\AdminReply;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reply_message' => 'required|string|max:2000',
        ];
    }
}