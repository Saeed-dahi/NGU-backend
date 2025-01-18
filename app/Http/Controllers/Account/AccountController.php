<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\Account\AccountResource;
use App\Http\Resources\Account\AccountStatementResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;
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
    public function show($id, Request $request)
    {
        $account = $id == 1 ? Account::first() : Account::find($id);
        $account = $this->navigateRecord($account, $request, 'code');

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
     * Get Suggestion code
     * @param Request
     * @return JsonResponse
     */

    function getSuggestionCode(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:accounts,id'
        ]);
        $parenAccount = Account::find($request->parent_id);
        $code = $this->accountService->getSuggestedCodePerParent($parenAccount);

        return $this->success($code);
    }

    /**
     * Search for account
     * @param Request
     * @return JsonResponse
     */

    function searchAccount(Request $request)
    {
        $request->validate([
            'query' => 'string|max:25'
        ]);
        $accounts = $this->accountService->searchAccount($request->search_query);

        return $this->success($accounts);
    }

    /**
     * Get Account Statement
     * @param Account
     * @return JsonResponse
     */
    function accountStatement(Account $account)
    {
        return $this->success(AccountStatementResource::make($account));
    }

    /**
     * Get Accounts Name With code
     * @param
     * @return JsonResponse
     */
    function getAccountsNameWithCode()
    {
        $accounts = Account::select(
            'id',
            'ar_name',
            'en_name',
            'code'
        )->get();
        return $this->success($accounts);
    }
}
