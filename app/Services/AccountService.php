<?php

namespace App\Services;

use App\Enum\Account\AccountType;
use App\Http\Traits\ApiResponser;
use App\Models\Account;
use Exception;

class AccountService
{
    use ApiResponser;

    function createNewAccount($request)
    {
        $validatedRequest = $request->validated();

        // check if parent account is Main
        if ($request->has('parent_id')) {
            $parentAccount = Account::findOrFail($request->parent_id);
            if (!$this->isMainAccount($parentAccount)) {
                throw new \Exception(__('lang.error_account_type'), 400);
            }
        }
        // Always budget is the the closing account
        $validatedRequest = array_merge($validatedRequest, ['closing_account_id' => 1]);


        $account = Account::create($validatedRequest);

        return $account;
    }

    function updateAccount($request, $account)
    {
        $validatedRequest = $request->validated();
        // TODO
        // if user change account code, change parent per new code
        // if ($request->has('code')) {
        //     $parent = $this->getParentFromCode($request->code);
        //     $validatedRequest['parent_id'] = $parent->id ?? null;
        // }
        $account->update($validatedRequest);

        return $account;
    }

    function searchAccount($query)
    {
        $accounts = Account::where('ar_name', 'LIKE', '%' . $query . '%')
            ->orWhere('en_name', 'LIKE', '%' . $query . '%')
            ->orWhere('code', 'LIKE', '%' . $query . '%')->get();

        return $accounts;
    }

    function isMainAccount($account)
    {
        return $account->account_type == AccountType::MAIN->value;
    }

    function isSubAccount($account)
    {
        return $account->account_type == AccountType::SUB->value;
    }

    function getParentFromCode($code)
    {
        for ($i = strlen($code) - 1; $i > 0; $i--) {
            $parentCode = substr($code, 0, $i);
            $parentAccount = Account::where('code', $parentCode)->first();
            if ($parentAccount) {
                if ($this->isMainAccount($parentAccount)) {
                    return $parentAccount;
                }
            }
        }
        return null;
    }

    function getSuggestedCodePerParent(Account $parentAccount)
    {
        $lastSub = $parentAccount->subAccounts()->latest('id')->first();
        if ($lastSub) {
            $suggestedCode = $lastSub->code + 1;
        } else {
            $suggestedCode = $parentAccount->code . '1';
        }


        return $suggestedCode;
    }
}
