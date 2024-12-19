<?php

namespace App\Http\Resources\ClosingAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClosingAccountsStatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'revenue_accounts' => CustomAccountResource::collection($this->debit_accounts),
            'expense_accounts' => CustomAccountResource::collection($this->credit_accounts),
            'expense' => $this->expense,
            'revenue' => $this->revenue,
            'value' => $this->value
        ];
    }
}
