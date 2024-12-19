<?php

namespace App\Http\Resources\ClosingAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomAccountResource extends JsonResource
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
            'code' => $this->code,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'balance' => abs($this->balance),
        ];
    }
}
