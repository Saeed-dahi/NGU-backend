<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'code' => $this->code,
            'description' => $this->description,
            'barcode' => $this->barcode,
            'type' => $this->type,
            'category_id' => $this->category_id,
            'file' => $this->file,
            'units' => ProductUnitResource::collection($this->productUnits()->where('base_product_unit_id', null)->get()),
        ];
    }
}
