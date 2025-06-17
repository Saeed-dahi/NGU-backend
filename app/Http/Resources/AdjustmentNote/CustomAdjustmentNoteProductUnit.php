<?php

namespace App\Http\Resources\AdjustmentNote;

use App\Http\Resources\Inventory\ProductResource;
use App\Http\Resources\Inventory\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomAdjustmentNoteProductUnit extends JsonResource
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
            'product' => new ProductResource($this->product, ['id', 'ar_name', 'en_name', 'code']),
            'unit' => new UnitResource($this->unit),
        ];
    }
}
