<?php

namespace App\Http\Requests\Invoice;

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\DiscountType;
use App\Enum\Invoice\InvoiceType;
use App\Enum\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
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
        if ($this->has('invoice')) {
            $this->merge($this->input('invoice'));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $invoiceId = $this->route('invoice');
        return [
            'invoice_number' => [
                'numeric',
                Rule::unique('invoices')->where(fn($query) => $query->where('type', $this->input('type')))
                    ->ignore($invoiceId),
            ],
            'document_number' => 'string|nullable',
            'type' => ['required', Rule::enum(InvoiceType::class)],
            'date' => 'required|date',
            'due_date' => 'date|nullable',
            'status' => ['required', Rule::enum(Status::class)],
            'invoice_nature' => [Rule::enum(AccountNature::class), 'nullable'],
            'address' => 'string|nullable',
            'currency' => 'string|nullable',
            'notes' => 'string|nullable',
            'account_id' => 'numeric|exists:accounts,id|required',
            'goods_account_id' => 'numeric|exists:accounts,id|required',
            'tax_account_id' => 'numeric|exists:accounts,id|required',
            'discount_account_id' => 'numeric|exists:accounts,id|required',
            'discount_type' => [Rule::enum(DiscountType::class), 'nullable'],
            'discount_amount' => 'numeric',
            'description' => 'string|nullable',
        ];
    }
}
