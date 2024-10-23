<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'type' => $this->type,
            'description' => $this->document,
            'amount' => $this->amount,
            'document_number' => $this->document_number,
            'account_name' => $this->account->ar_name,
            'account_new_balance' => $this->account_new_balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
