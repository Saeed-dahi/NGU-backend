<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChequeRequest;
use App\Http\Resources\ChequeResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Cheque;
use App\Services\ChequeServices;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class ChequeController extends Controller
{
    use ApiResponser, SharedFunctions;

    protected $chequeServices, $transactionService;

    public function __construct(ChequeServices $chequeServices, TransactionService $transactionService)
    {
        $this->chequeServices = $chequeServices;
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cheques = Cheque::all();
        return $this->success(ChequeResource::collection($cheques));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ChequeRequest $request)
    {
        $cheque = Cheque::create($request->validated());

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
    public function update(ChequeRequest $request, Cheque $cheque)
    {
        $cheque->update($request->validated());

        $this->transactionService->deleteTransactions($cheque);
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
}
