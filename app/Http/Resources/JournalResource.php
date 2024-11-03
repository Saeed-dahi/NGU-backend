<?php

namespace App\Http\Resources;

use App\Http\Traits\SharedFunctions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalResource extends JsonResource
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
            'document' => $this->document,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->customDateFormat($this->created_at, 'Y-m-d'),
            'updated_at' => $this->customDateFormat($this->updated_at, 'Y-m-d'),
            'transactions' => TransactionResource::collection($this->transactions)
        ];
    }
}
