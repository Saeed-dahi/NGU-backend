<?php

namespace App\Http\Resources;

use App\Http\Traits\SharedFunctions;
use App\Models\ClosingAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    use SharedFunctions;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->{$lang . '_name'},
            'account_type' => $this->account_type,
            'account_nature' => $this->account_nature,
            'account_category' => $this->account_category,
            'balance' => $this->calculateBalance(),
            // 'closing_account' => ClosingAccountResource::make($this->ClosingAccount),
            // 'account_information' => AccountInformationResource::make($this->AccountInformation),
            'sub_accounts' => AccountResource::collection($this->subAccounts),
            // 'parent' => AccountResource::make($this->parentAccount),
            'parent_id' => $this->parentAccount->id ?? null,
            'created_at' => $this->customDateFormat($this->created_at),
            'updated_at' => $this->customDateFormat($this->updated_at),
        ];
    }
}
