<?php

namespace App\Services\AdjustmentNote;

use App\Http\Requests\AdjustmentNote\PreviewAdjustmentNoteItemRequest;
use App\Http\Traits\SharedFunctions;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductUnit;
use App\Models\Invoice\InvoiceItems;

class AdjustmentNoteItemsService
{

    use SharedFunctions;
    function validateAdjustmentNoteItemsRequest()
    {
        $validatedData = request()->validate([
            'items' => 'array|required',
            'items.*.product_unit_id' => ['required', 'exists:product_units,id'],
            'items.*.description' => '',
            'items.*.quantity' => '',
            'items.*.price' => '',
            'items.*.tax_amount' => '',
        ]);
        return $validatedData;
    }

    function createAdjustmentNoteItems($adjustmentNote, $validatedData)
    {
        foreach ($validatedData as $key => $entry) {
            info($entry);
            $entry['total'] = $entry['price'] * $entry['quantity'];
            $entry['tax_amount'] = ($entry['total'] * $adjustmentNote->tax_amount) / 100;
            $adjustmentNote->items()->create($entry);
        }

        $adjustmentNote->total = $this->calculateTax($adjustmentNote->sub_total, $adjustmentNote->tax_amount);
        $adjustmentNote->save();
    }

    /**
     * Delete All AdjustmentNote per AdjustmentNote
     * @param AdjustmentNote
     * @return Void
     */
    function deleteAdjustmentNoteItems($adjustmentNote)
    {
        foreach ($adjustmentNote->items as $key => $item) {
            $item->delete();
        }
    }


    /**
     * Get Invoice Item Data when add new on to the invoice
     * @param Query,ProductUnitId,AccountId
     * @return InvoiceItem
     */
    function previewAdjustmentNoteItem(PreviewAdjustmentNoteItemRequest $request)
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
                'tax_amount' => ($price * $quantity) * 0.05,
                'sub_total' => $price * $quantity,
                'total' => $this->calculateTax($price * $quantity, 5),
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
