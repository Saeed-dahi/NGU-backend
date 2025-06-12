<?php

namespace App\Models\AdjustmentNote;

use App\Models\Inventory\ProductUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'adjustment_note_id',
        'product_unit_id',
        'quantity',
        'price',
        'tax_amount',
        'discount_amount',
        'total',
    ];

    function adjustmentNote()
    {
        return $this->belongsTo(AdjustmentNote::class, 'adjustment_note_id');
    }
    function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }
}
