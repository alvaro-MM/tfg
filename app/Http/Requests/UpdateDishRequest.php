<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDishRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust as needed: may use policies to restrict updates
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'ingredients' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'slug' => ['sometimes', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'available' => ['sometimes', 'boolean'],
            'special' => ['sometimes', 'boolean'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'allergen_ids' => ['nullable', 'array'],
            'allergen_ids.*' => ['integer', 'exists:allergens,id'],
        ];
    }

}