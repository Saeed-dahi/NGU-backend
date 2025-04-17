<?php

namespace App\Models;

use App\Models\Account\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'cheque_number',
        'amount',
        'nature',
        'image',
        'date',
        'due_date',
        'status',
        'notes',
        'issued_from_account_id',
        'issued_to_account_id',
        'target_bank_account_id'
    ];

    public function issuedFromAccount()
    {
        return $this->belongsTo(Account::class, 'issued_from_account_id');
    }

    public function issuedToAccount()
    {
        return $this->belongsTo(Account::class, 'issued_to_account_id');
    }

    public function targetBankAccount()
    {
        return $this->belongsTo(Account::class, 'target_bank_account_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }
}
