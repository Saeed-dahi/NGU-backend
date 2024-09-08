<?php

namespace App\Http\Requests\Account;

use App\Enum\Account\AccountCategory;
use App\Enum\Account\AccountNature;
use App\Enum\Account\AccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateAccountRequest extends FormRequest
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
        // to avoid unique check in update existing account
        $accountId = $this->account ? $this->account->id : -1;

        return [
            'code' => [Rule::unique('Accounts', 'code')->ignore($accountId),],
            'en_name' => [Rule::unique('Accounts', 'en_name')->ignore($accountId),],
            'ar_name' => [Rule::unique('Accounts', 'ar_name')->ignore($accountId),],
            'account_type' => [Rule::enum(AccountType::class)],
            'account_nature' => [Rule::enum(AccountNature::class)],
            'account_category' => [Rule::enum(AccountCategory::class)],
            'parent_id' => 'exists:Accounts,id|nullable',
            'closing_account_id' => 'exists:closing_accounts,id',
        ];
    }
}
