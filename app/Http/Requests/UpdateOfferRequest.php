<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('offer'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $offerId = $this->route('offer')->id;

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:offers,slug,' . $offerId,
            'description' => 'required|string|max:1000',
            'discount' => 'required|integer|min:1|max:100',
            'menu_id' => 'required|exists:menus,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'slug' => 'slug',
            'description' => 'descripción',
            'discount' => 'descuento',
            'menu_id' => 'menú',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'slug.unique' => 'El slug ya está en uso.',
            'menu_id.exists' => 'El menú seleccionado no existe.',
        ];
    }
}
