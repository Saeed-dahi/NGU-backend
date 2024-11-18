<?php

namespace App\Services;

use App\Enum\Account\AccountType;
use App\Http\Traits\ApiResponser;
use App\Models\Account;

class TransactionService
{
    use ApiResponser;

    function createTransactions($transactable, $validatedData)
    {
        foreach ($validatedData as $key => $entry) {
            $account = Account::where('code', $entry['account_id'])->first();
            $entry['account_id'] = $account->id;
            $entry['date'] = $transactable->date;
            $transaction = $transactable->transactions()->create($entry);

            // Update the newly created transaction with the correct account balance
            $transaction->update(['account_new_balance' => $transaction->account->balance]);
        }
    }

    function validateTransactionRequest()
    {
        $validatedData = request()->validate([
            'entries' => 'array|required',
            'entries.*.account_id' => [
                'required',
                'exists:accounts,code',
                function ($attribute, $value, $fail) {
                    $account = Account::where('code', $value)->first();
                    if ($account->account_type == AccountType::MAIN->value) {
                        $fail(__('lang.error_account_type') . ' ' . $account->ar_name . '-' . $account->en_name);
                    }
                },
            ],
            'entries.*.type' => 'required|in:credit,debit',
            'entries.*.amount' => 'required|numeric',
            'entries.*.description' => '',
            'entries.*.document_number' => '',
        ]);

        return $validatedData;
    }

    function deleteTransactions($transactable)
    {
        foreach ($transactable->transactions as $key => $transaction) {
            $transaction->delete();
        }
    }
}
