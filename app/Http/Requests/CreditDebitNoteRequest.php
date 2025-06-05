<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreditDebitNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $creditDebitNote = $this->route('credit_debit_note');
        return [
            'number' => Rule::unique('credit_debit_notes')->ignore($creditDebitNote),
            'document_number' => 'nullable|string|max:255',
            'type' => 'required|in:debit,credit',
            'status' => 'required|in:draft,saved',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'description' => 'required|string|max:1000',
            'sub_total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'primary_account_id' => 'required|exists:accounts,id',
            'secondary_account_id' => 'required|exists:accounts,id',
            'tax_account_id' => 'required|exists:accounts,id',
            'tax_amount' => 'required|numeric|min:0',
            'cheque_id' => 'nullable|exists:cheques,id'
        ];
    }
}
