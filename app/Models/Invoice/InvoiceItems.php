<?php

namespace App\Models\Invoice;

use App\Enum\Invoice\InvoiceType;
use App\Enum\Status;
use App\Models\Inventory\ProductUnit;
use App\Services\Invoice\InvoiceItemsService;
use App\Services\ProductUnitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_unit_id',
        'description',
        'quantity',
        'price',
        'tax_amount',
        'discount_amount',
        'total',
        'date',
        'product_unit_new_quantity'
    ];


    function scopeSavedInvoiceItems($query)
    {
        return $query->whereHas('invoice', function ($query) {

            $query->where('status', Status::SAVED->value);
        });
    }


    protected static function boot()
    {
        parent::boot();

        static::created(function ($invoiceItem) {
            $productUnitInvoiceItemsQuery = $invoiceItem->productUnit->invoiceItems()->savedInvoiceItems();

            $productUnitInvoiceItems = $productUnitInvoiceItemsQuery->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

            $productUnitService = new ProductUnitService();
            $productUnitService->updateProductUnitQuantityAutomatically($invoiceItem->productUnit, $productUnitInvoiceItems);

            $invoiceItemsService = new InvoiceItemsService();
            $invoiceItemsService->updateInvoiceItemQuantity($invoiceItem->productUnit->quantity, $productUnitInvoiceItems);
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }
}
