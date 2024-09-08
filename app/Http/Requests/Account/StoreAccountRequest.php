<?php

namespace App\Http\Requests\Account;

use App\Enum\Account\AccountCategory;
use App\Enum\Account\AccountNature;
use App\Enum\Account\AccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreAccountRequest extends FormRequest
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
            'code' => 'required|unique:accounts',
            'en_name' => 'required|unique:accounts',
            'ar_name' => 'required|unique:accounts',
            'account_type' => [Rule::enum(AccountType::class)],
            'account_nature' => [Rule::enum(AccountNature::class)],
            'account_category' => [Rule::enum(AccountCategory::class)],
            'parent_id' => 'exists:Accounts,id|nullable',
            'closing_account_id' => 'required|exists:closing_accounts,id',
        ];
    }
}
