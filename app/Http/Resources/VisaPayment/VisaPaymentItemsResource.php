<?php

namespace App\Http\Resources\VisaPayment;

use App\Http\Resources\Account\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaPaymentItemsResource extends JsonResource
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
        return [
            'id' => $this->id,
            'visa_payment_id' => $this->visa_payment_id,
            'customer_account' => $this->getCustomAccountResource($this->customerAccount),
            'description' => $this->description,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
