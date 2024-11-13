<?php

namespace App\Http\Resources;

use App\Http\Traits\SharedFunctions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    use SharedFunctions;
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
            'description' => $this->description,
            'amount' => $this->amount,
            'document_number' => $this->document_number,
            'account_name' => $this->account->ar_name . ' - ' . $this->account->en_name,
            'account_code' => $this->account->code,
            'account_new_balance' => $this->account_new_balance,
            'created_at' => $this->customDateFormat($this->created_at, 'Y-m-d'),
            'updated_at' => $this->customDateFormat($this->updated_at, 'Y-m-d'),

        ];
    }
}
