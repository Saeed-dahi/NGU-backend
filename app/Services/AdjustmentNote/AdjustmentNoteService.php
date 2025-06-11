<?php

namespace App\Services\AdjustmentNote;

use App\Enum\Account\AccountNature;
use App\Http\Traits\SharedFunctions;
use App\Services\TransactionService;

class AdjustmentNoteService
{
    use SharedFunctions;
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    function createAdjustmentTransaction($adjustmentNote)
    {
        switch ($adjustmentNote->type) {
            case AccountNature::CREDIT->value:
                $creditTransactions = $this->prepareCreditAdjustmentNoteTransactions($adjustmentNote, $adjustmentNote->type);
                $this->transactionService->createTransactions($adjustmentNote, $creditTransactions);
                break;
            case AccountNature::DEBIT->value:
                $debitTransactions = $this->prepareDebitAdjustmentNoteTransactions($adjustmentNote, $adjustmentNote->type);
                $this->transactionService->createTransactions($adjustmentNote, $debitTransactions);
                break;
        }
    }


    function prepareCreditAdjustmentNoteTransactions($adjustmentNote, $adjustmentNoteType)
    {
        $transactions = [];

        // Customer Account
        $transactions[] = [
            'account_id' => $adjustmentNote->primary_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $adjustmentNote->total,
            'description' => request()->description ?? $adjustmentNoteType,
            'document_number' => $adjustmentNote->adjustmentNote_number,
        ];

        $transactions[] = [
            'account_id' => $adjustmentNote->secondary_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $adjustmentNote->sub_total,
            'description' => request()->description ?? $adjustmentNoteType,
            'document_number' => $adjustmentNote->adjustmentNote_number,
        ];

        // Tax Account
        if ($adjustmentNote->tax_account_id && $adjustmentNote->tax_amount > 0) {
            $transactions[] = [
                'account_id' => $adjustmentNote->tax_account_id,
                'type' => AccountNature::DEBIT,
                'amount' => $adjustmentNote->sub_total * ($adjustmentNote->tax_amount / 100), // Assuming tax is a percentage
                'description' => request()->description ?? $adjustmentNoteType,
                'document_number' => $adjustmentNote->adjustmentNote_number,
            ];
        }


        return $transactions;
    }

    function prepareDebitAdjustmentNoteTransactions($adjustmentNote, $adjustmentNoteType)
    {
        $transactions = [];

        // Customer Account
        $transactions[] = [
            'account_id' => $adjustmentNote->account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $adjustmentNote->total,
            'description' => request()->description ?? $adjustmentNoteType,
            'document_number' => $adjustmentNote->adjustmentNote_number,
        ];

        $transactions[] = [
            'account_id' => $adjustmentNote->secondary_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $adjustmentNote->sub_total,
            'description' => request()->description ?? $adjustmentNoteType,
            'document_number' => $adjustmentNote->adjustmentNote_number,
        ];

        // Tax Account
        if ($adjustmentNote->tax_account_id && $adjustmentNote->tax_amount > 0) {
            $transactions[] = [
                'account_id' => $adjustmentNote->tax_account_id,
                'type' => AccountNature::CREDIT,
                'amount' => $adjustmentNote->sub_total * ($adjustmentNote->tax_amount / 100), // Assuming tax is a percentage
                'description' => request()->description ?? $adjustmentNoteType,
                'document_number' => $adjustmentNote->adjustmentNote_number,
            ];
        }


        return $transactions;
    }
}
