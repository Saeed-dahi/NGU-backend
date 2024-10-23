<?php

namespace App\Http\Resources;

use App\Enum\Account\AccountNature;
use App\Enum\Account\AccountType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountStatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transactions' => $this->transactions,
            'debit_balance' => $this->transactions()->where('type', AccountNature::DEBIT->value)->sum('amount'),
            'credit_balance' => $this->transactions()->where('type', AccountNature::CREDIT->value)->sum('amount'),
            'balance' => $this->balance
        ];
    }
}
