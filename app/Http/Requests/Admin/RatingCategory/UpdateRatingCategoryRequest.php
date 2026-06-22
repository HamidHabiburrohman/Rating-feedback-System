<?php

namespace App\Http\Requests\Admin\RatingCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRatingCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('rating_category') ?? $this->route('id'); 
        
        return [
            'name'          => ['required', 'string', 'max:255', Rule::unique('rating_categories', 'name')->ignore($categoryId)],
            'slug'          => ['nullable', 'string', 'max:255', Rule::unique('rating_categories', 'slug')->ignore($categoryId)],
            'description'   => 'nullable|string|max:1000',
            'is_active'     => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
            'min_score'     => 'nullable|numeric|min:0',
            'max_score'     => 'nullable|numeric|gte:min_score',
            'default_score' => 'nullable|numeric',
        ];
    }
}