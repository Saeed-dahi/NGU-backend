<?php

namespace App\Models\AdjustmentNote;

use App\Models\Account\Account;
use App\Models\Cheque;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentNote extends Model
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

    function primaryAccount()
    {
        return $this->belongsTo(Account::class, 'primary_account_id');
    }

    function secondaryAccount()
    {
        return $this->belongsTo(Account::class, 'secondary_account_id');
    }

    function taxAccount()
    {
        return $this->belongsTo(Account::class, 'tax_account_id');
    }

    function cheque()
    {
        return $this->belongsTo(Cheque::class, 'cheque_id');
    }

    function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    function adjustmentNoteItems()
    {
        return $this->hasMany(AdjustmentNoteItem::class);
    }
}
