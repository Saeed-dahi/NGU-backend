<?php

namespace App\Models\Invoice;

use App\Models\Account\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'type',
        'date',
        'due_date',
        'status',
        'invoice_nature',
        'currency',
        'sub_total',
        'total',
        'notes',
        'account_id',
        'goods_account_id',
        'total_tax_account',
        'total_tax',
        'total_discount_account',
        'total_discount',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            $lastInvoice = Invoice::latest()->firstWhere('type', $invoice->type);
            $newNumber = $invoice->invoice_number ?? ($lastInvoice ? $lastInvoice->invoice_number + 1 : 1);
            $invoice->invoice_number = $newNumber;
        });
    }

    public function items()
    {
        return $this->hasMany(InvoiceItems::class);
    }


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function goodsAccount()
    {
        return $this->belongsTo(Account::class, 'goods_account_id');
    }

    public function taxAccount()
    {
        return $this->belongsTo(Account::class, 'total_tax_account');
    }
    public function discountAccount()
    {
        return $this->belongsTo(Account::class, 'total_discount_account');
    }
}
