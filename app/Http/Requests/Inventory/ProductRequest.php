<?php

namespace App\Http\Requests\Inventory;

use App\Enum\Inventory\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProductRequest extends FormRequest
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
        $productId = $this->route('product');
        return [
            'ar_name' => 'string|required',
            'en_name' => 'string|required',
            'code' => [
                'string',
                'required',
                Rule::unique('products', 'code')->ignore($productId),
            ],
            'description' => 'string|nullable',
            'barcode' => 'string|nullable',
            'type' => [Rule::enum(ProductType::class)],
            'category_id' => 'numeric|required|exists:categories,id',
            'file' => 'array',
            'file.*' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
}
