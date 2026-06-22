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
            'name'          => 'required|string|max:255|unique:rating_categories,name',
            'slug'          => 'nullable|string|max:255|unique:rating_categories,slug',
            'is_active'     => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
            'min_score'     => 'nullable|numeric|min:0',
            'max_score'     => 'nullable|numeric|gte:min_score',
            'default_score' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah digunakan.',
            'max_score.gte' => 'Skor maksimal harus lebih besar atau sama dengan skor minimal.',
        ];
    }
}
