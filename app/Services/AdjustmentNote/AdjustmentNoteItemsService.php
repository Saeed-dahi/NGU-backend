<?php

namespace App\Services\AdjustmentNote;

use App\Http\Traits\SharedFunctions;

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
}
