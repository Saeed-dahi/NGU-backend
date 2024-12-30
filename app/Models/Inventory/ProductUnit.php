<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'unit_id',
        'base_product_unit_id',
        'conversion_factor',
        'export_price',
        'import_price',
        'wholesale_price',
        'end_price',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    function subUnit()
    {
        return $this->hasOne(ProductUnit::class, 'base_product_unit_id');
    }

    function parentUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'base_product_unit_id');
    }
}
