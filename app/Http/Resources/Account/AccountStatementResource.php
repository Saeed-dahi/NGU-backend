<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

class AccountStatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transactions = $this->allTransactions();

        return [
            'transactions' => TransactionResource::collection($transactions),
            'debit_balance' => $this->debitBalance(),
            'credit_balance' => $this->creditBalance(),
            'balance' => $this->balance
        ];
    }
}
