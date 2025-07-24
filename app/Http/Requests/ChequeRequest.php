<?php

namespace App\Http\Requests;

use App\Enum\Cheque\ChequeDiscountType;
use App\Enum\Cheque\ChequeNature;
use App\Enum\Cheque\ChequeStatus;
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
            'cheque_number' => ['required','numeric'],
            'amount' => 'required|numeric|min:0',
            'date' => 'date',
            'due_date' => 'required|date',
            'image' => 'array', // Validate that it's an array of files
            'image.*' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
            'status' => [Rule::enum(ChequeStatus::class), 'required'],
            'discount_type' => [Rule::enum(ChequeDiscountType::class), 'nullable'],
            'discount_amount' => 'nullable|numeric',
            'nature' => [Rule::enum(ChequeNature::class), 'required'],
            'notes' => 'nullable|string|max:255',
            'issued_from_account_id' => 'required|exists:accounts,id',
            'issued_to_account_id' => 'required|exists:accounts,id',
            'target_bank_account_id' => 'required|exists:accounts,id',
        ];
    }
}
