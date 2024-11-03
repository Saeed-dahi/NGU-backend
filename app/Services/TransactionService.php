<?php

namespace App\Services;

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
            $transaction = $transactable->transactions()->create($entry);

            // Update the newly created transaction with the correct account balance
            $transaction->update(['account_new_balance' => $transaction->account->balance]);
        }
    }

    function validateTransactionRequest()
    {
        $validatedData = request()->validate([
            'entries' => 'array|required',
            // 'entries.*.account_id' => 'required|exists:accounts,id',
            'entries.*.account_id' => 'required',
            'entries.*.type' => 'required|in:credit,debit',
            'entries.*.amount' => 'required|numeric',
            'entries.*.description' => 'string',
            'entries.*.document_number' => 'string',
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
