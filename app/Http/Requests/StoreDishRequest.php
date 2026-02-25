<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'available' => $this->boolean('available'),
            'special' => $this->boolean('special'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'ingredients' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'available' => ['boolean'],
            'special' => ['boolean'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['integer', 'exists:allergens,id'],
        ];
    }
}
