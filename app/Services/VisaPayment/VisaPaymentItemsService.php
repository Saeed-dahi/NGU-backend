<?php

namespace App\Services\VisaPayment;

use App\Enum\Account\AccountNature;

use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\VisaPayment\VisaPayment;
use App\Services\TransactionService;

class VisaPaymentItemsService
{
    use ApiResponser, SharedFunctions;

    protected $visaPaymentServices, $transactionsService;

    public function __construct(?VisaPaymentService $visaPaymentServices = null, TransactionService $transactionsService)
    {
        $this->visaPaymentServices = $visaPaymentServices;
        $this->transactionsService = $transactionsService;
    }

    /**
     * Validate VisaPaymentItems Request
     * @param
     * @return ValidatedData
     */
    function validateVisaPaymentItemsRequest()
    {
        $validatedData = request()->validate([
            'items' => 'array|required',
            'items.*visa_payment_id' => 'required|exists:visa_payments,id',
            'items.*customer_account_id' => 'required|exists:accounts,id',
            'items.*amount' => 'required|numeric|min:0',
            'items.*notes' => 'nullable|string|max:255',
        ]);
        return $validatedData;
    }

    /**
     * Create New VisaPayment Item
     * @param VisaPayment,Data
     * @return Void
     */
    function createVisaPaymentItems(VisaPayment $visaPayment, $validatedData)
    {
        foreach ($validatedData as $key => $entry) {
            $visaPaymentItem =   $visaPayment->items()->create($entry);
            $this->createItemTransaction($visaPaymentItem, $visaPayment);
        }
    }

    /**
     * Create Transaction for each VisaPaymentItem
     * @param VisaPaymentItem,VisaPayment
     * @return Void
     */
    function createItemTransaction($visaPaymentItem, $visaPayment)
    {
        // Customer Account
        $transactions[] = [
            'account_id' => $visaPaymentItem->customer_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $visaPaymentItem->amount,
            'description' => 'visa_payment',
            'document_number' => $visaPayment->document_number,
        ];

        $this->transactionsService->createTransactions($visaPaymentItem, $transactions);
    }

    /**
     * Delete All VisaPaymentItems per VisaPayment
     * @param VisaPayment
     * @return Void
     */
    function deleteVisaPaymentItems($visaPayment)
    {
        foreach ($visaPayment->items as $key => $item) {
            $this->transactionsService->deleteTransactions($item);
            $item->delete();
        }
    }
}
