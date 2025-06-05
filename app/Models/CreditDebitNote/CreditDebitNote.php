<?php

namespace App\Models\CreditDebitNote;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDebitNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'document_number',
        'type',
        'status',
        'date',
        'due_date',
        'description',
        'sub_total',
        'total',
        'primary_account_id',
        'secondary_account_id',
        'tax_account_id',
        'tax_amount',
        'cheque_id',
    ];

    
}
