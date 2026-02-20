<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'dish_id' => 'nullable|exists:dishes,id',
            'drink_id' => 'nullable|exists:drinks,id',
            'rating' => 'nullable|integer|between:1,5',
            'image' => 'nullable|image|max:2048'
        ];
    }
}
