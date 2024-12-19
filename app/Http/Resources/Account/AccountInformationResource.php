<?php

namespace App\Http\Resources\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountInformationResource extends JsonResource
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
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'fax' => $this->fax,
            'email' => $this->email,
            'contact_person_name' => $this->contact_person_name,
            'closing_account' => $this->account->closingAccount->{app()->getLocale() . '_name'},
            'address' => $this->address,
            'description' => $this->description,
            'info_in_invoice' => $this->info_in_invoice,
            'barcode' => $this->barcode,
            'file' => $this->file
        ];
    }
}
