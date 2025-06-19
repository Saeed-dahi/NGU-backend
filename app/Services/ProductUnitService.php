<?php

namespace App\Services;

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\InvoiceType;
use App\Enum\Status;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;

class ProductUnitService
{
    use ApiResponser, SharedFunctions;


    /**
     * this function will help me to automatic update account balance depend on it's invoiceItems
     * @param Account,invoiceItems (we can get it use relationship)
     * @return Void
     */
    function updateProductUnitQuantityAutomatically($productUnit, $invoiceItems)
    {
        $newQuantity = 0;
        foreach ($invoiceItems as $key => $invoiceItem) {
            if (
                $invoiceItem->invoice->type == InvoiceType::SALES->value ||
                $invoiceItem->invoice->type == InvoiceType::PURCHASE_RETURN->value
            ) {
                $newQuantity += (-$invoiceItem->quantity);
            } else {
                $newQuantity += ($invoiceItem->quantity);
            }
        }
        $productUnit->update(['quantity' => $newQuantity]);
    }

    /**
     * TODO: fix this function
     */
    function updateAccountBalance($invoiceItem)
    {
        $account = $invoiceItem->account;
        $transactable = $invoiceItem->transactable;
        $amount = $invoiceItem->type == AccountNature::DEBIT->value ? $invoiceItem->amount : -$invoiceItem->amount;
        $incrementAmount  = $transactable->status == Status::SAVED->value ?  +$amount :  -$amount;

        if ($transactable->created_at != $transactable->updated_at) {
            $account->increment('balance', $incrementAmount);
        }
    }
}
