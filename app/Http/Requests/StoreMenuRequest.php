<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:daily,special,seasonal,themed',
            'price' => 'required|numeric|min:0|max:999.99',
            'offer_id' => 'nullable|exists:offers,id',
            'dish_ids' => 'nullable|array',
            'dish_ids.*' => 'exists:dishes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del menú es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'type.required' => 'El tipo de menú es obligatorio.',
            'type.in' => 'El tipo de menú debe ser uno de los siguientes: diario, especial, estacional, temático.',
            'price.required' => 'El precio del menú es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio debe ser mayor o igual a 0.',
            'price.max' => 'El precio no puede ser mayor a 999.99.',
            'offer_id.exists' => 'La oferta seleccionada no existe.',
            'dish_ids.array' => 'Los platos deben ser un arreglo.',
            'dish_ids.*.exists' => 'Uno o más platos seleccionados no existen.',
        ];
    }
}
