<?php

namespace App\Http\Resources;

use App\Http\Traits\SharedFunctions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class ClosingAccountResource extends JsonResource
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
            'ar_name' => $this->ar_name,
            'en_name' => $this->en_name,
            'created_at' => $this->customDateFormat($this->created_at),
            'updated_at' => $this->customDateFormat($this->updated_at),

        ];
    }
}
