<?php

namespace App\Http\Requests\Admin\RatingCategory;

use Illuminate\Foundation\Http\FormRequest;

class ReorderCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:rating_categories,id',
            'categories.*.sort_order' => 'required|integer|min:0'
    ];
    }
}