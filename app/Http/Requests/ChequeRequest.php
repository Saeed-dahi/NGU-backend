<?php

namespace App\Http\Requests;

use ChequeNature;
use ChequeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChequeRequest extends FormRequest
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
            'cheque_number' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'date',
            'due_date' => 'required|date',
            'image' => 'file',
            'status' => [Rule::enum(ChequeStatus::class), 'required'],
            'nature' => [Rule::enum(ChequeNature::class), 'required'],
            'notes' => 'nullable|string|max:255',
            'issued_from_account_id' => 'required|exists:accounts,id',
            'issued_to_account_id' => 'required|exists:accounts,id',
            'target_bank_account_id' => 'required|exists:accounts,id',
        ];
    }
}
