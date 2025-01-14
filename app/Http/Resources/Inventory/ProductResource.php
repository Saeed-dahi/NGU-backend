<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    private $fields;

    public function __construct($resource, $fields = null)
    {
        // Call the parent constructor
        parent::__construct($resource);

        // Store the additional parameter
        $this->fields = $fields;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'code' => $this->code,
            'description' => $this->description,
            'barcode' => $this->barcode,
            'type' => $this->type,
            'category' => CategoryResource::make($this->category),
            'file' => $this->file,
            'units' =>  ProductUnitResource::collection($this->productUnits),
        ];
        return $this->fields ? array_intersect_key($data, array_flip($this->fields)) : $data;
    }
}
