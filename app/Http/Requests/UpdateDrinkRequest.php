<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDrinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],

            'slug'        => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('drinks', 'slug')->ignore($this->drink),
            ],

            'description' => ['required', 'string'],

            'price'       => ['required', 'numeric', 'min:0'],
            'available'   => ['sometimes', 'boolean'],

            'image'       => ['nullable', 'image', 'max:2048'],

            'category_id' => ['required', 'exists:categories,id'],

            'allergen_ids'   => ['nullable', 'array'],
            'allergen_ids.*' => ['exists:allergens,id'],
        ];
    }
}
