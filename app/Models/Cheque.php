<?php

namespace App\Models;

use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;
use App\Models\AdjustmentNote\AdjustmentNote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory, SharedFunctions;

    protected $fillable = [
        'cheque_number',
        'amount',
        'nature',
        'image',
        'date',
        'due_date',
        'status',
        'discount_type',
        'discount_amount',
        'notes',
        'issued_from_account_id',
        'issued_to_account_id',
        'target_bank_account_id',
        'discount_account_id'
    ];


    protected $casts = [
        'image' => 'array'
    ];

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public";
        $destination_path = "uploads/cheques/" . str_replace(' ', '_', $this->cheque_number);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

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

    function adjustmentNote()
    {
        return $this->hasOne(AdjustmentNote::class, 'cheque_id');
    }
}
