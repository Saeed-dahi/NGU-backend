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

    static function boot()
    {
        parent::boot();
        static::updated(function ($productUnit) {
            $subUnit = $productUnit->subUnit;
            if ($subUnit) {
                $conversionFactor = $productUnit->conversion_factor;

                $subUnit->export_price = $productUnit->export_price / $conversionFactor;
                $subUnit->import_price = $productUnit->import_price / $conversionFactor;
                $subUnit->wholesale_price = $productUnit->wholesale_price / $conversionFactor;
                $subUnit->end_price = $productUnit->end_price / $conversionFactor;

                $subUnit->save();
            }
        });
    }

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
