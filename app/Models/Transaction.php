<?php

namespace App\Models;

use App\Enum\Account\AccountNature;
use App\Enum\Status;
use App\Services\AccountService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'type', 'amount', 'description', 'document_number', 'account_new_balance', 'date'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactable()
    {
        return $this->morphTo();
    }

    public function getAmountAttribute($value)
    {
        return round($this->attributes['amount'], 2);
    }

    public function scopeSavedTransactable($query)
    {
        return $query->whereHas('transactable', function ($query) {
            $query->where('status', Status::SAVED->value);
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($transaction) {
            if ($transaction->transactable->status == Status::SAVED->value) {
                $accountService = new AccountService();
                $accountService->updateAccountBalance($transaction, true);
            }
        });

        static::created(function ($transaction) {
            if ($transaction->transactable->status == Status::SAVED->value) {
                $accountService = new AccountService();
                $accountService->updateAccountBalance($transaction);
            }
        });
    }
}
