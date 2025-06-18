<?php

namespace App\Http\Resources\Invoice;

use App\Http\Resources\Inventory\ProductUnitResource;
use App\Models\Inventory\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'product_unit' => new CustomInvoiceProductUnit($this->productUnit),
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'tax_amount' => $this->tax_amount,
            'discount_amount' => $this->discount_amount,
            'total' => $this->total,
        ];
    }
}
