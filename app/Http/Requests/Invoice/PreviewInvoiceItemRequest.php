<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class PreviewInvoiceItemRequest extends FormRequest
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
            'query' => 'required|string',
            'account_id' => 'nullable|exists:accounts,id',
            'product_unit_id' => 'nullable|integer|exists:product_units,id',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'change_unit' => 'nullable'
        ];
    }
}
