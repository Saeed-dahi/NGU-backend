<?php

namespace App\Services;

use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItems;

class InvoiceItemsService
{
    use ApiResponser, SharedFunctions;

    /**
     * Validate InvoiceItems Request
     * @param
     * @return ValidatedData
     */
    function validateInvoiceItemsRequest()
    {
        $validatedData = request()->validate([
            'items' => 'array|required',
            'items.*.product_unit_id' => ['required', 'exists:product_units,id'],
            'items.*.description' => '',
            'items.*.quantity' => '',
            'items.*.price' => '',
            'items.*.tax_amount' => '',
            'items.*.discount_amount' => '',
        ]);
        return $validatedData;
    }

    /**
     * Create New Invoice Item
     * @param Invoice,Data
     * @return Void
     */
    function createInvoiceItems(Invoice $invoice, $validatedData)
    {
        $invoiceSubTotal = 0;
        foreach ($validatedData as $key => $entry) {
            $entry['total'] = $entry['price'] * $entry['quantity'];
            $invoice->items()->create($entry);

            $invoiceSubTotal += $entry['total'];
        }
        $invoice->sub_total = $invoiceSubTotal;

        $invoice->total = $this->calculateTax($invoiceSubTotal, $invoice->total_tax);
        $invoice->save();
    }

    /**
     * Delete All InvoiceItems per Invoice
     * @param Invoice
     * @return Void
     */
    function deleteInvoiceItems($invoice)
    {
        foreach ($invoice->items as $key => $item) {
            $item->delete();
        }
    }
}
