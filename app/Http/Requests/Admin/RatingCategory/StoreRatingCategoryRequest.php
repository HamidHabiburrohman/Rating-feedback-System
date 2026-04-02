<?php

namespace App\Http\Requests\Admin\RatingCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:rating_categories,name',
            'slug' => 'required|string|max:255|unique:rating_categories,slug',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}