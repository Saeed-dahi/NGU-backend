<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponser, SharedFunctions;

    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::whereNull('parent_id')->get();

        return $this->success(AccountResource::collection($accounts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        try {
            $account = $this->accountService->createNewAccount($request);

            return $this->success(AccountResource::make($account));
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), $th->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account, Request $request)
    {
        $account = $this->navigateRecord($account, $request);

        return $this->success(AccountResource::make($account));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account = $this->accountService->updateAccount($request, $account);

        return $this->success(AccountResource::make($account));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        // TODO: Delete account with conditions
        // $account->delete();
        return $this->success(null);
    }

    /**
     * Search for specific Account
     */
    function searchAccount(Request $request)
    {
        $request->validate([
            'query' => 'string|max:25'
        ]);
        $accounts = $this->accountService->searchAccount($request->search_query);

        return $this->success($accounts);
    }
}
