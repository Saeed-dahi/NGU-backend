<?php

namespace App\Http\Resources\VisaPayment;

use App\Http\Resources\Account\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaPaymentResource extends JsonResource
{

    private $fields;


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
            'document_number' => $this->document_number,
            'gross_amount' => $this->gross_amount,
            'commission_rate' => $this->commission_rate,
            'commission_amount' => $this->commission_amount,
            'tax_amount' => $this->tax_amount,
            'net_amount' => $this->net_amount,
            'status' => $this->status->value, // Assuming status is an enum
            'date' => $this->customDateFormat($this->date, 'Y-m-d'),
            'due_date' => $this->customDateFormat($this->due_date, 'Y-m-d'),
            'notes' => $this->notes,
            'bank_account' => $this->getCustomAccountResource($this->bankAccount),
            'machine_account' => $this->getCustomAccountResource($this->machineAccount),
            'commission_account' => $this->getCustomAccountResource($this->commissionAccount),
            'tax_account' => $this->getCustomAccountResource($this->taxAccount),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => VisaPaymentItemsResource::collection($this->items),

        ];
        return $this->fields ? array_intersect_key($data, array_flip($this->fields)) : $data;
    }
}
