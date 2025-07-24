<?php

namespace App\Models\VisaPayment;

use App\Models\Account\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_number',
        'gross_amount',
        'commission_rate',
        'commission_amount',
        'tax_amount',
        'net_amount',
        'date',
        'due_date',
        'notes',
        'bank_account_id',
        'machine_account_id',
        'commission_account_id',
        'tax_account_id'
    ];


    function items()
    {
        return $this->hasMany(VisaPaymentItems::class);
    }

    function bankAccount()
    {
        return $this->belongsTo(Account::class, 'bank_account_id');
    }
    function machineAccount()
    {
        return $this->belongsTo(Account::class, 'machine_account_id');
    }
    function commissionAccount()
    {
        return $this->belongsTo(Account::class, 'commission_account_id');
    }

    function taxAccount()
    {
        return $this->belongsTo(Account::class, 'tax_account_id');
    }
}
