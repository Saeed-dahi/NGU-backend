<?php

namespace App\Services;

use App\Enum\Account\AccountNature;
use App\Enum\Account\AccountType;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;

class TransactionService
{
    use ApiResponser, SharedFunctions;

    /**
     * Validate Transaction Request
     * @param
     * @return ValidatedData
     */
    function validateTransactionRequest()
    {
        $validatedData = request()->validate([
            'transactions' => 'array|required',
            'transactions.*.account_id' => [
                'required',
                'exists:accounts,id',
                function ($attribute, $value, $fail) {
                    $account = Account::where('id', $value)->first();
                    if ($account->account_type == AccountType::MAIN->value) {
                        $fail(__('lang.error_account_type') . ' ' . $account->ar_name . '-' . $account->en_name);
                    }
                },
            ],
            'transactions.*.type' => 'required|in:credit,debit',
            'transactions.*.amount' => 'required|numeric',
            'transactions.*.description' => '',
            'transactions.*.document_number' => '',
        ]);

        return $validatedData;
    }

    /**
     * Create New Transaction
     * @param Transactable,Data
     * @return Void
     */
    function createTransactions($transactable, $validatedData, $date = null)
    {
        foreach ($validatedData as $key => $entry) {
            $account = Account::where('id', $entry['account_id'])->first();
            $entry['account_id'] = $account->id;
            $entry['date'] = $this->addNowTimeToDate($date ?? $transactable->date);
            $transactable->transactions()->create($entry);
        }
    }

    /**
     * Delete All Transaction per transactable
     * @param Transactable
     * @return Void
     */
    function deleteTransactions($transactable)
    {
        foreach ($transactable->transactions as $key => $transaction) {
            $transaction->delete();
        }
    }

    /**
     * Get Next transaction per date
     * @param Account,Date
     * @return Array_of_transaction
     */
    function getNextTransactions(Account $account, $date)
    {
        $nextTransactions = $account->transactions()->savedTransactable()->where('date', '>', $date)->get();

        return $nextTransactions;
    }

    /**
     * Get Previous transaction per date
     * @param Account,Date
     * @return Array_of_transaction
     */
    function getPreviousTransactions(Account $account, $date)
    {
        $previousTransactions = $account->transactions()->savedTransactable()->where('date', '<', $date)->get();

        return $previousTransactions;
    }

    /**
     * Update All Transactions Balance depend on current balance
     * @param CurrentBalance,Array_of_transactions
     * @return Void
     */
    function updateTransactionsBalance($currentBalance, $transactions)
    {
        foreach ($transactions as $key => $transaction) {
            $currentTransactionAmount = $transaction->type == AccountNature::DEBIT->value ? -$transaction->amount : +$transaction->amount;

            $transaction->update(['account_new_balance' => $currentBalance]);

            $currentBalance += $currentTransactionAmount;
        }
    }
}
