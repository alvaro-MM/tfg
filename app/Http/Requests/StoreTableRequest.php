<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:tables,name',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved',
            'notes' => 'nullable|string',

            // relaciones
            'menu_id' => 'nullable|exists:menus,id',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
