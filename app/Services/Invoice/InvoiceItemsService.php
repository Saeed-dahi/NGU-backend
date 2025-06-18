<?php

namespace App\Services\Invoice;

use App\Http\Requests\Invoice\PreviewInvoiceItemRequest;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductUnit;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItems;

class InvoiceItemsService
{
    use ApiResponser, SharedFunctions;

    protected $invoiceServices;

    public function __construct(InvoiceService $invoiceServices)
    {
        $this->invoiceServices = $invoiceServices;
    }

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
            $invoiceItemSubTotal = $entry['price'] * $entry['quantity'];
            $entry['total'] = $this->calculateTotalWithTax($invoiceItemSubTotal);
            $entry['tax_amount'] = $this->calculateTaxAmount($invoiceItemSubTotal);
            $invoice->items()->create($entry);

            $invoiceSubTotal += $invoiceItemSubTotal;
        }
        $invoice->sub_total = $this->invoiceServices->calculateInvoiceSubTotalAfterDiscount($invoice, $invoiceSubTotal);
        $invoice->tax_amount = $this->calculateTaxAmount($invoiceSubTotal);
        $invoice->total = $this->calculateTotalWithTax($invoice->sub_total);
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
    function invoiceItemPreview(PreviewInvoiceItemRequest $request)
    {
        $query = $request['query'];
        $productUnitId = $request['product_unit_id'];
        $quantity = $request['quantity'] ?? 1;

        $product = Product::where('code', $query)->orWhere('ar_name', $query)->orWhere('en_name', $query)->firstOrFail();

        $productUnit = $this->selectProductUnit($productUnitId, $product, $request['change_unit']);
        $lastPrice = $this->getInvoiceItemPricePerAccount($request['account_id'], $productUnit);
        $price = $request['change_unit'] ? $lastPrice : $request['price'] ?? $lastPrice;

        $data = [
            'id' => $product->id,
            'ar_name' => $product->ar_name,
            'en_name' => $product->en_name,
            'code' => $product->code,
            'product_unit' => [
                'id' => $productUnit->id,
                'ar_name' => $productUnit->unit->ar_name,
                'en_name' => $productUnit->unit->en_name,
                'unit_id' => $productUnit->unit->id,
                'price' => $price,
                'tax_amount' => $this->calculateTaxAmount($price * $quantity),
                'sub_total' => $price * $quantity,
                'total' => $this->calculateTotalWithTax($price * $quantity),
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

    function selectProductUnit($productUnitId, $product, $changeUnit)
    {
        $productUnit = $productUnitId ? $product->productUnits()->find($productUnitId) : $product->productUnits()->first();

        if ($changeUnit) {
            $productUnit = $product->productUnits()->where('id', '>', $productUnit->id)->first() ??
                $product->productUnits()->where('id', '<', $productUnit->id)->latest('id')->first();
        }

        return $productUnit;
    }
}
