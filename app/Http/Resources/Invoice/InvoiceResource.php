<?php

namespace App\Http\Resources\Invoice;

use App\Http\Resources\Account\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    private $fields;

    public function __construct($resource, $fields = null)
    {
        // Call the parent constructor
        parent::__construct($resource);

        // Store the additional parameter
        $this->fields = $fields;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $customAccountFields = ['id', 'code', 'ar_name', 'en_name'];
        $data = [
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
            'account' => new AccountResource($this->account, $customAccountFields),
            'goods_account' => new AccountResource($this->goodsAccount, $customAccountFields),
            'tax_account' => new AccountResource($this->taxAccount, $customAccountFields),
            'total_tax' => $this->total_tax,
            'discount_account' => new AccountResource($this->discountAccount, $customAccountFields),
            'total_discount' => $this->total_discount,
            'items' => InvoiceItemsResource::collection($this->items),
        ];

        return $this->fields ? array_intersect_key($data, array_flip($this->fields)) : $data;
    }
}
