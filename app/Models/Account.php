<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'ar_name',
        'en_name',
        'account_type',
        'account_nature',
        'account_category',
        'parent_id',
        'balance',
        'closing_account_id'
    ];

    public function calculateBalance()
    {
        info('1');
        $balance = $this->balance;

        foreach ($this->subAccounts as $subAccount) {
            $balance += $subAccount->calculateBalance(); // Recursively add the balance of sub accounts
        }

        //  if we want t save the account balance in db
        // $this->balance = $balance;
        // $this->save();

        return $balance;
    }


    static function boot()
    {
        parent::boot();
        static::created(function ($account) {
            AccountInformation::create([
                'account_id' => $account->id,
            ]);
        });
    }

    function AccountInformation()
    {
        return $this->hasOne(AccountInformation::class);
    }

    function ClosingAccount()
    {
        return $this->belongsTo(ClosingAccount::class);
    }

    function subAccounts()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }
}
