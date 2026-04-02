<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable',
            'type' => 'required|string|in:string,integer,boolean,json,float,text,array',
            'group' => 'required|string|max:100',
            'subgroup' => 'nullable|string|max:100',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'options' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
            'is_editable' => 'sometimes|boolean',
            'is_visible' => 'sometimes|boolean',
            'is_public' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_editable' => $this->boolean('is_editable', true),
            'is_visible' => $this->boolean('is_visible', true),
            'is_public' => $this->boolean('is_public', false),
        ]);
    }
}