<?php

namespace App\Http\Resources;

use App\Http\Resources\Account\AccountResource;
use App\Http\Traits\SharedFunctions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChequeResource extends JsonResource
{
    use SharedFunctions;

    function getCustomAccountResource($account)
    {
        $customAccountFields = ['id', 'code', 'ar_name', 'en_name'];
        $customAccountResource = new AccountResource($account, $customAccountFields);

        return $customAccountResource;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'cheque_number' => $this->cheque_number,
            'status' => $this->status,
            'date' => $this->customDateFormat($this->date, 'Y-m-d'),
            'due_date' => $this->customDateFormat($this->due_date, 'Y-m-d'),
            'nature' => $this->nature,
            'image' => $this->image,
            'notes' => $this->notes,
            'issued_from_account' => $this->getCustomAccountResource($this->issuedFromAccount),
            'issued_to_account' => $this->getCustomAccountResource($this->issuedToAccount),
            'target_bank_account' => $this->getCustomAccountResource($this->targetBankAccount),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
