<?php

namespace App\Services\VisaPayment;

use App\Enum\Account\AccountNature;
use App\Enum\VisaPayment\VisaPaymentStatus;
use App\Http\Traits\SharedFunctions;
use App\Models\VisaPayment\VisaPayment;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class VisaPaymentService
{
    use SharedFunctions;
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    function createVisaPaymentTransactions($visaPayment)
    {
        $salesTransactions = $this->prepareVisaPaymentTransactions($visaPayment, 'visa_payment');
        $this->transactionService->createTransactions($visaPayment, $salesTransactions);
    }

    function prepareVisaPaymentTransactions($visaPayment, $desc)
    {
        $transactions = [];

        // Visa Machine Account
        $transactions[] = [
            'account_id' => $visaPayment->machine_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $visaPayment->net_amount,
            'description' => request()->description ?? $desc,
            'document_number' => $visaPayment->document_number,
        ];

        // Visa Commission Account
        $transactions[] = [
            'account_id' => $visaPayment->commission_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $visaPayment->commission_amount,
            'description' => request()->description ?? $desc,
            'document_number' => $visaPayment->document_number,
        ];

        // Tax Account
        if ($visaPayment->tax_account_id && $visaPayment->tax_amount > 0) {
            $transactions[] = [
                'account_id' => $visaPayment->tax_account_id,
                'type' => AccountNature::CREDIT,
                'amount' => $visaPayment->tax_amount,
                'description' => request()->description ?? $desc,
                'document_number' => $visaPayment->document_number,
            ];
        }


        return $transactions;
    }


    public function customVisaPaymentNavigateRecord($visaPayments, Model $model, Request $request, $column = 'id')
    {
        $direction = $request->input('direction');

        switch ($direction) {
            case 'next':
                $record = $visaPayments->where($column, '>', $model->$column)->first() ?? $visaPayments->first();
                break;
            case 'previous':
                $record = $visaPayments->where($column, '<', $model->$column)->latest($column)->first() ?? $visaPayments->latest($column)->first();
                break;
            case 'first':
                $record = $visaPayments->first();
                break;
            case 'last':
                $record = $visaPayments->latest($column)->first();
                break;
        }


        return $record ?? $model;
    }


    function depositVisaPayment(VisaPayment $visaPayment)
    {
        $visaPayment->status = VisaPaymentStatus::DEPOSITED->value;
        $visaPayment->save();

        $this->createDepositedChequeTransactions($visaPayment);
    }

    function createDepositedChequeTransactions(VisaPayment $visaPayment)
    {
        $transactions = $this->prepareDepositedChequeTransactions($visaPayment);

        $this->transactionService->createTransactions($visaPayment, $transactions);
    }

    function prepareDepositedChequeTransactions(VisaPayment $visaPayment)
    {
        $transactions = [];

        // Issued to Account
        $transactions[] = [
            'account_id' => $visaPayment->bank_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $visaPayment->net_amount,
            'description' => 'visa_payment',
            'document_number' => $visaPayment->document_number,
        ];

        $transactions[] = [
            'account_id' => $visaPayment->machine_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $visaPayment->net_amount,
            'description' => 'visa_payment',
            'document_number' => $visaPayment->document_number,
        ];


        return $transactions;
    }
}
