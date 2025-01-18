<?php

namespace App\Models\Account;

use App\Enum\Account\AccountNature;
use App\Models\ClosingAccount;
use App\Models\Transaction;
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
        $balance = $this->balance;

        foreach ($this->subAccounts as $subAccount) {
            $balance += $subAccount->calculateBalance(); // Recursively add the balance of sub accounts
        }
        //  if we want t save the account balance in db
        // $this->balance = $balance;
        // $this->save();

        return $balance;
    }

    public function debitBalance()
    {
        return $this->transactions()
            ->where('type', AccountNature::DEBIT->value)
            ->savedTransactable()
            ->sum('amount');
    }


    public function creditBalance()
    {
        return $this->transactions()
            ->where('type', AccountNature::CREDIT->value)
            ->savedTransactable()
            ->sum('amount');
    }

    public function allTransactions()
    {
        $transactions = collect();

        if ($this->subAccounts()->exists()) {
            foreach ($this->subAccounts as $key => $subAccount) {
                $transactions = $transactions->merge($subAccount->allTransactions());
            }
        }

        $transactions = $transactions->merge(
            $this->transactions()->savedTransactable()->orderBy('date')->orderBy('id')->get()
        );

        return $transactions;
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
        return $this->hasMany(Account::class, 'parent_id')->orderBy('code');
    }

    function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
