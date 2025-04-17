<?php

namespace App\Services;

use App\Enum\Account\AccountNature;
use App\Enum\Cheque\ChequeNature;
use App\Enum\Cheque\ChequeStatus;
use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;
use App\Models\Cheque;

class ChequeServices
{
    use SharedFunctions;
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    function createChequeTransactions(Cheque $cheque)
    {
        switch ($cheque->nature) {
            case ChequeNature::INCOMING->value:
                $incomingChequeTransaction = $this->prepareIncomingChequeTransactions($cheque);
                $this->transactionService->createTransactions($cheque, $incomingChequeTransaction);
                break;

            case ChequeNature::OUTGOING->value:
                $incomingChequeTransaction = $this->prepareOutgoingChequeTransactions($cheque);
                $this->transactionService->createTransactions($cheque, $incomingChequeTransaction);
                break;
        }
    }

    function prepareIncomingChequeTransactions(Cheque $cheque)
    {
        $transactions = [];
        // Issued from Account
        $transactions[] = [
            'account_id' => $cheque->issued_from_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $cheque->amount,
            'description' => 'cheque',
            'document_number' => $cheque->cheque_number,
        ];

        // Issued to Account
        $transactions[] = [
            'account_id' => $cheque->issued_to_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $cheque->amount,
            'description' => 'cheque',
            'document_number' => $cheque->cheque_number,
        ];

        return $transactions;
    }

    function prepareOutgoingChequeTransactions(Cheque $cheque)
    {
        $transactions = [];
        // Issued from Account
        $transactions[] = [
            'account_id' => $cheque->issued_from_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $cheque->amount,
            'description' => 'cheque',
            'document_number' => $cheque->cheque_number,
        ];

        // Issued to Account
        $transactions[] = [
            'account_id' => $cheque->issued_to_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $cheque->amount,
            'description' => 'cheque',
            'document_number' => $cheque->cheque_number,
        ];

        return $transactions;
    }

    function depositCheque(Cheque $cheque)
    {
        $cheque->status = ChequeStatus::DEPOSITED->value;
        $cheque->save();

        $this->createDepositedChequeTransactions($cheque);
    }

    function createDepositedChequeTransactions(Cheque $cheque)
    {
        $transactions = $this->prepareDepositedChequeTransactions($cheque);

        foreach ($transactions as $key => $entry) {
            $account = Account::where('id', $entry['account_id'])->first();
            $entry['account_id'] = $account->id;
            $entry['date'] = $this->addNowTimeToDate($this->customDateFormat($cheque->due_date, 'Y-m-d'));
            $cheque->transactions()->create($entry);
        }
    }

    function prepareDepositedChequeTransactions(Cheque $cheque)
    {
        $transactions = [];
        switch ($cheque->nature) {
            case ChequeNature::INCOMING->value:
                // Issued to Account
                $transactions[] = [
                    'account_id' => $cheque->issued_to_account_id,
                    'type' => AccountNature::CREDIT,
                    'amount' => $cheque->amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];

                $transactions[] = [
                    'account_id' => $cheque->target_bank_account_id,
                    'type' => AccountNature::DEBIT,
                    'amount' => $cheque->amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];
                break;

            case ChequeNature::OUTGOING->value:
                $transactions[] = [
                    'account_id' => $cheque->issued_to_account_id,
                    'type' => AccountNature::DEBIT,
                    'amount' => $cheque->amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];

                $transactions[] = [
                    'account_id' => $cheque->target_bank_account_id,
                    'type' => AccountNature::CREDIT,
                    'amount' => $cheque->amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];
                break;
        }

        return $transactions;
    }
}
