<?php

namespace App\Models\Invoice;

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
        'total_tax_account',
        'total_tax',
        'total_discount_account',
        'total_discount',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItems::class);
    }


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }
}
