<?php

namespace App\Http\Requests\VisaPayment;

use App\Enum\VisaPayment\VisaPaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisaPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('visa_payment')) {
            $this->merge($this->input('visa_payment'));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_number' => 'required|integer',
            'gross_amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'status' => [Rule::enum(VisaPaymentStatus::class), 'required'],
            'date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'bank_account_id' => 'required|exists:accounts,id',
            'machine_account_id' => 'required|exists:accounts,id',
            'commission_account_id' => 'required|exists:accounts,id',
            'tax_account_id' => 'nullable|exists:accounts,id',
        ];
    }
}
