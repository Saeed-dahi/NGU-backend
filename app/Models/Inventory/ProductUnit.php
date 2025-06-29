<?php

namespace App\Models\Inventory;

use App\Enum\Invoice\InvoiceType;
use App\Enum\Status;
use App\Models\AdjustmentNote\AdjustmentNoteItem;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItems;
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

    public function getLastPurchaseDetails($beforeDate)
    {
        return $this->invoiceItems::select('invoice_items.price', 'invoices.date')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoice_items.product_unit_id', $this->id)
            ->where('invoices.type', InvoiceType::PURCHASE->value)
            ->where('invoices.status', Status::SAVED->value)
            ->when($beforeDate, function ($query) use ($beforeDate) {
                $query->whereDate('invoices.date', '<=', $beforeDate);
            })
            ->orderByDesc('invoices.date')
            ->limit(1)
            ->first();
    }

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

    public function invoiceItems()
    {
        return $this->hasOne(InvoiceItems::class);
    }

    public function adjustmentNoteItems()
    {
        return $this->hasMany(AdjustmentNoteItem::class);
    }
}
