<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ProductUnitRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'unit_id' => 'required|integer|exists:units,id',
            'base_product_unit_id' => 'integer|exists:product_units,id',
            'conversion_factor' => 'numeric',
            'export_price' => 'numeric',
            'import_price' => 'numeric',
            'wholesale_price' => 'numeric',
            'end_price' => 'numeric',
            'quantity' => 'numeric'
        ];
    }
}
