<?php

namespace App\Services;

use App\Enum\Account\AccountNature;
use App\Enum\Cheque\ChequeDiscountType;
use App\Enum\Cheque\ChequeNature;
use App\Enum\Cheque\ChequePaymentCases;
use App\Enum\Cheque\ChequeStatus;
use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;
use App\Models\Cheque;
use Carbon\Carbon;

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
        $chequeDiscountTransactions = $this->prepareDiscountTransactions($cheque);
        $this->transactionService->createTransactions($cheque, $chequeDiscountTransactions);
    }

    function validateMultipleChequeRequest($request)
    {
        $request->validate([
            'multiple_cheques_params.cheques_count' => 'required',
            'multiple_cheques_params.each_payment' => 'required',
            'multiple_cheques_params.first_payment' => 'required',
            'multiple_cheques_params.last_payment' => 'required',
            'multiple_cheques_params.payment_way' => 'required',
            'multiple_cheques_params.payment_way_count' => 'required',
        ]);
    }

    function createMultipleCheques($request)
    {
        $multipleChequeParams = $request->multiple_cheques_params;
        $dueDate = $request->due_date;
        $chequeNumber = $request->cheque_number;


        for ($i = 1; $i <= $multipleChequeParams['cheques_count']; $i++) {

            $amount = $this->getAmountByPaymentType($i, $multipleChequeParams);

            $preparedChequeData = $this->buildChequeData($amount, $chequeNumber, $dueDate, $request);

            $cheque =  Cheque::create($preparedChequeData);
            $this->createChequeTransactions($cheque);

            $chequeNumber++;
            $dueDate = $this->getCustomDueDateByPaymentWay(Carbon::parse($dueDate), $multipleChequeParams['payment_way'], $multipleChequeParams['payment_way_count']);
        }
    }

    function getAmountByPaymentType($i, $multipleChequeParams)
    {
        $amount = $multipleChequeParams['each_payment'];
        if ($i == 1 && $multipleChequeParams['first_payment'] != 0) {
            $amount = $multipleChequeParams['first_payment'];
        }
        if ($i == $multipleChequeParams['cheques_count'] && $multipleChequeParams['last_payment'] != 0) {
            $amount = $multipleChequeParams['last_payment'];
        }

        return $amount;
    }

    function getCustomDueDateByPaymentWay(Carbon $currentDate, $paymentWay, $paymentWayCount)
    {
        $newDate = '';
        switch ($paymentWay) {
            case ChequePaymentCases::MONTHLY->value:
                $newDate =   $currentDate->addMonth()->format('Y-m-d');
                break;
            case ChequePaymentCases::EACH_WEEK->value:
                $newDate = $currentDate->addWeek()->format('Y-m-d');
                break;
            case ChequePaymentCases::EACH_FOUR_WEEKS->value:
                $newDate = $currentDate->addWeeks(4)->format('Y-m-d');
                break;
            case ChequePaymentCases::SPECIFIC_DAYS->value:
                $newDate = $currentDate->addDays($paymentWayCount)->format('Y-m-d');
                break;
            case ChequePaymentCases::SPECIFIC_MONTHS->value:
                $newDate = $currentDate->addMonths($paymentWayCount)->format('Y-m-d');
                break;
        }
        return $newDate;
    }

    private function buildChequeData($amount, $chequeNumber, $dueDate, $request)
    {
        return [
            'amount' => $amount,
            'cheque_number' => $chequeNumber,
            'status' => $request->status,
            'date' => $request->date,
            'due_date' => $dueDate,
            'nature' => $request->nature,
            'notes' => $request->notes,
            'issued_from_account_id' => $request->issued_from_account_id,
            'issued_to_account_id' => $request->issued_to_account_id,
            'target_bank_account_id' => $request->target_bank_account_id,
        ];
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

    function prepareDiscountTransactions(Cheque $cheque)
    {
        $transactions = [];
        switch ($cheque->discount_type) {
            case ChequeDiscountType::ALLOWED->value:
                $transactions[] = [
                    'account_id' => $cheque->issued_from_account_id,
                    'type' => AccountNature::CREDIT,
                    'amount' => $cheque->discount_amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];
                $transactions[] = [
                    'account_id' => $cheque->discount_account_id,
                    'type' => AccountNature::DEBIT,
                    'amount' => $cheque->discount_amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];

                break;
            case ChequeDiscountType::RECEIVED->value:
                $transactions[] = [
                    'account_id' => $cheque->issued_from_account_id,
                    'type' => AccountNature::DEBIT,
                    'amount' => $cheque->discount_amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];
                $transactions[] = [
                    'account_id' => $cheque->discount_account_id,
                    'type' => AccountNature::CREDIT,
                    'amount' => $cheque->discount_amount,
                    'description' => 'cheque',
                    'document_number' => $cheque->cheque_number,
                ];
                break;
        }

        return $transactions;
    }
}
