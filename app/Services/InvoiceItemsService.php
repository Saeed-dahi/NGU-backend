<?php

namespace App\Services;

use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductUnit;
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
            $entry['tax_amount'] = ($entry['total'] * $invoice->total_tax) / 100;
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


    /**
     * Get Invoice Item Data when add new on to the invoice
     * @param Query,ProductUnitId,AccountId
     * @return InvoiceItem
     */
    function invoiceItemPreview(string $query,  $productUnitId,  $accountId)
    {
        $product = Product::where('code', $query)->orWhere('ar_name', $query)->orWhere('en_name', $query)->firstOrFail();

        $productUnit = $productUnitId ? $product->productUnits()->where('unit_id', $productUnitId)->first() : $product->productUnits()->first();
        $price =  $this->getInvoiceItemPricePerAccount($accountId, $productUnit);


        $data = [
            'id' => $product->id,
            'ar_name' => $product->ar_name,
            'en_name' => $product->en_name,
            'code' => $product->code,
            'unit' => [
                'id' => $productUnit->id,
                'ar_name' => $productUnit->unit->ar_name,
                'en_name' => $productUnit->unit->en_name,
                'unit_id' => $productUnit->unit->id,
                'price' => $price,
            ]
        ];
        return $data;
    }

    /**
     * @param AccountId,ProductUnit
     * @return Price
     */
    function getInvoiceItemPricePerAccount($accountId, ProductUnit $productUnit)
    {
        return InvoiceItems::whereHas('invoice', function ($query) use ($accountId) {
            $query->where('account_id', $accountId);
        })->where('product_unit_id', $productUnit->id)->latest('id')
            ->value('price') ?? $productUnit->end_price;
    }
}
