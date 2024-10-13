<?php

namespace App\Http\Requests\AccountInformation;

use Illuminate\Foundation\Http\FormRequest;

class AccountInformationRequest extends FormRequest
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
            'account_id' => 'integer|exists:Accounts,id',
            'phone' => 'integer|nullable',
            'mobile' => 'integer|nullable',
            'fax' => 'integer|nullable',
            'email' => 'email|nullable',
            'contact_person_name' => 'string|nullable',
            'address' => 'string|nullable',
            'description' => 'string|nullable',
            'info_in_invoice' => 'string|nullable',
            'barcode' => 'string|nullable',
            'file' => 'array', // Validate that it's an array of files
            'file.*' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
}
