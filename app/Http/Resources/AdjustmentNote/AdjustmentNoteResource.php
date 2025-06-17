<?php

namespace App\Http\Resources\AdjustmentNote;

use App\Http\Resources\Account\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentNoteResource extends JsonResource
{

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
        return
            [
                'id' => $this->id,
                'number' => $this->number,
                'document_number' => $this->document_number,
                'type' => $this->type,
                'status' => $this->status,
                'date' => $this->date,
                'due_date' => $this->due_date,
                'description' => $this->description,
                'sub_total' => $this->sub_total,
                'total' => $this->total,
                'primary_account' => $this->getCustomAccountResource($this->primaryAccount),
                'secondary_account' => $this->getCustomAccountResource($this->secondaryAccount),
                'tax_account' => $this->getCustomAccountResource($this->taxAccount),
                'tax_amount' => $this->tax_amount,
                'cheque_id' => $this->cheque_id,
                'items' => AdjustmentNoteItemsResource::collection($this->items),
            ];
    }
}
