<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChequeRequest;
use App\Http\Resources\ChequeResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Account\Account;
use App\Models\Cheque;
use App\Services\AccountService;
use App\Services\ChequeServices;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChequeController extends Controller
{
    use ApiResponser, SharedFunctions;

    protected $chequeServices, $transactionService, $accountService;

    public function __construct(ChequeServices $chequeServices, TransactionService $transactionService, AccountService $accountService)
    {
        $this->chequeServices = $chequeServices;
        $this->transactionService = $transactionService;
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cheques = Cheque::orderBy('date', 'DESC')->get();
        return $this->success(ChequeResource::collection($cheques));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ChequeRequest $request)
    {
        $requestData = array_merge($request->all(), ['image' => []]);
        $cheque = Cheque::create($requestData);

        $this->chequeServices->createChequeTransactions($cheque);

        return $this->success(ChequeResource::make($cheque));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $cheque = $id == 1 ? Cheque::firstOrFail() : Cheque::findOrFail($id);
        $cheque = $this->navigateRecord($cheque, $request);

        return $this->success(ChequeResource::make($cheque));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update($id, ChequeRequest $request)
    {
        $cheque = Cheque::findOrFail($id);
        $request = array_merge($request->all(), ['image' => []]);

        $cheque->update($request);

        $this->transactionService->deleteTransactions($cheque);

        // when remove all transactions we should re calculate the bank account balance (because we will not create a new one now)
        $targetBankAccount = $cheque->targetBankAccount;
        $targetBankAccountTransactions = $targetBankAccount->transactions()->savedTransactable()->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        $this->accountService->updateAccountBalanceAutomatically($targetBankAccount, $targetBankAccountTransactions);

        $this->chequeServices->createChequeTransactions($cheque);
        $cheque->load('transactions');

        return $this->success(ChequeResource::make($cheque));
    }


    function depositCheque($id)
    {
        $cheque = Cheque::findOrFail($id);
        $this->chequeServices->depositCheque($cheque);

        return $this->success(ChequeResource::make($cheque));
    }

    /**
     * Get Accounts Name With code
     * @param Id
     * @return JsonResponse
     */
    function getChequesPerAccount(Account $account)
    {
        $cheques = $account->cheques()->orderBy('date', 'DESC')->get();

        return $this->success(ChequeResource::collection($cheques));
    }

    /**
     * Get Accounts Name With code
     * @param Id
     * @return JsonResponse
     */
    function createMultipleCHeques(ChequeRequest $request)
    {
        $this->chequeServices->createMultipleCHeques($request);

        return true;
    }
}
