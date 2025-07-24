<?php

namespace App\Http\Requests\VisaPayment;

use Illuminate\Foundation\Http\FormRequest;

class VisaPaymentItemsRequest extends FormRequest
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
            'visa_payment_id' => 'required|exists:visa_payments,id',
            'customer_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
