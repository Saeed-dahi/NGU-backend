<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'type' => $this->type,
            'date' => $this->date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'invoice_nature' => $this->invoice_nature,
            'currency' => $this->currency,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'notes' => $this->notes,
            'account_id' => $this->account_id,
            'total_tax_account' => $this->total_tax_account,
            'total_tax' => $this->total_tax,
            'total_discount_account' => $this->total_discount_account,
            'total_discount' => $this->total_discount,
            'items' => InvoiceItemsResource::collection($this->items),
        ];
    }
}
