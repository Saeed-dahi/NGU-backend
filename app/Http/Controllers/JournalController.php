<?php

namespace App\Http\Controllers;

use App\Http\Requests\JournalRequest;
use App\Http\Resources\JournalResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Journal;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    use ApiResponser, SharedFunctions;

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $journals = Journal::all();
        return $this->success(JournalResource::collection($journals));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(JournalRequest $journalRequest)
    {

        $validatedData = $this->transactionService->validateTransactionRequest();

        $journal = Journal::create($journalRequest->validated());

        $this->transactionService->createTransactions($journal, $validatedData['entries']);

        return $this->success(JournalResource::make($journal));
    }

    /**
     * Display the specified resource.
     */
    public function show(Journal $journal, Request $request)
    {
        $journal = $this->navigateRecord($journal, $request);
        return $this->success(JournalResource::make($journal));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JournalRequest $journalRequest, Journal $journal)
    {
        $validatedData = $this->transactionService->validateTransactionRequest();

        $journal->update($journalRequest->validated());

        $this->transactionService->deleteTransactions($journal);

        $this->transactionService->createTransactions($journal, $validatedData['entries']);
        // update $journal->transaction
        $journal->load('transactions');

        return $this->success(JournalResource::make($journal));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal)
    {
        // TODO: check if we need to delete journal
    }
}
