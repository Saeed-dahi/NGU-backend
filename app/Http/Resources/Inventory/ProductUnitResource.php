<?php

namespace App\Http\Resources\Inventory;

use App\Models\Inventory\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
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
            'product_id' => $this->product_id,
            'name' => $this->unit->{app()->getLocale() . '_name'},

            'unit_id' => $this->unit_id,
            'sub_unit' => ProductUnitResource::make($this->subUnit),
            'conversion_factor' => $this->conversion_factor,
            'export_price' => $this->export_price,
            'import_price' => $this->import_price,
            'wholesale_price' => $this->wholesale_price,
            'end_price' => $this->end_price,
            'quantity' => $this->quantity
        ];
    }
}
