<?php

namespace App\Models\VisaPayment;

use App\Models\Account\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaPaymentItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_payment_id',
        'customer_account_id',
        'amount',
        'notes'
    ];


    function visaPayment()
    {
        return $this->belongsTo(VisaPayment::class, 'visa_payment_id');
    }

    function customerAccount()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }
}
