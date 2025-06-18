<?php

namespace App\Http\Resources\Invoice;

use App\Http\Resources\Account\AccountResource;
use App\Http\Traits\SharedFunctions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    use SharedFunctions;
    private $fields;

    public function __construct($resource, $fields = null)
    {
        // Call the parent constructor
        parent::__construct($resource);

        // Store the additional parameter
        $this->fields = $fields;
    }

    function getCustomAccountResource($account)
    {
        $customAccountFields = ['id', 'code', 'ar_name', 'en_name'];
        $customAccountResource = new AccountResource($account, $customAccountFields);

        $transaction = $account->transactions()->where('transactable_id', $this->id)->first();
        if ($transaction) {
            $additionalResource = [
                'description' => $transaction->description,
            ];
            return array_merge($customAccountResource->toArray(request()), $additionalResource);
        }
        return $customAccountResource;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'document_number' => $this->document_number,
            'type' => $this->type,
            'date' => $this->customDateFormat($this->date, 'Y-m-d'),
            'due_date' => $this->customDateFormat($this->due_date, 'Y-m-d'),
            'status' => $this->status,
            'invoice_nature' => $this->invoice_nature,
            'address' => $this->address,
            'currency' => $this->currency,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'notes' => $this->notes,
            'description' => $this->transactions[0]->description,
            'account' => $this->getCustomAccountResource($this->account),
            'goods_account' => $this->getCustomAccountResource($this->goodsAccount),
            'tax_account' => $this->getCustomAccountResource($this->taxAccount),
            'discount_account' => $this->getCustomAccountResource($this->discountAccount),
            'discount_amount' => $this->discount_amount,
            'discount_type' => $this->discount_type,
            'items' => InvoiceItemsResource::collection($this->items),
        ];

        return $this->fields ? array_intersect_key($data, array_flip($this->fields)) : $data;
    }
}
