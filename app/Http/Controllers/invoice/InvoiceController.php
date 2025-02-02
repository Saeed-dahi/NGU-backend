<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Invoice\Invoice;
use App\Services\InvoiceItemsService;
use App\Services\InvoiceService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ApiResponser, SharedFunctions;


    protected $invoiceItemsService, $invoiceService, $transactionService;

    public function __construct(
        InvoiceItemsService $invoiceItemsService,
        InvoiceService $invoiceService,
        TransactionService $transactionService
    ) {
        $this->invoiceItemsService = $invoiceItemsService;
        $this->invoiceService = $invoiceService;
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $invoiceRequest)
    {
        $validatedItems = $this->invoiceItemsService->validateInvoiceItemsRequest();

        $validatedData = $invoiceRequest->validated();

        $validatedData['total_tax'] = 5;
        $validatedData['total_discount'] = 0;
        $invoice = Invoice::create($validatedData);

        $this->invoiceItemsService->createInvoiceItems($invoice, $validatedItems['items']);
        $this->invoiceService->createInvoiceTransaction($invoice);

        return $this->success(InvoiceResource::make($invoice));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $request->validate(['type' => 'required']);

        $invoicesQuery = Invoice::where('type', $request->type);
        $invoices = $invoicesQuery->get();

        $invoice = $id == 1 ? $invoices->first() : $invoices->where('id', $id)->first() ?? abort(404);
        $invoice = $this->invoiceService->customInvoiceNavigateRecord($invoicesQuery, $invoice, $request);

        return $this->success(InvoiceResource::make($invoice));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $invoiceRequest,  $id)
    {
        $invoice = Invoice::where('type', $invoiceRequest->type)->findOrFail($id);
        $validatedData = $this->invoiceItemsService->validateInvoiceItemsRequest();

        $invoice->update($invoiceRequest->validated());

        $this->invoiceItemsService->deleteInvoiceItems($invoice);
        $this->invoiceItemsService->createInvoiceItems($invoice, $validatedData['items']);
        $invoice->load('items');

        $this->transactionService->deleteTransactions($invoice);

        $this->invoiceService->createInvoiceTransaction($invoice);
        // update $journal->transaction
        $invoice->load('transactions');

        return $this->success(InvoiceResource::make($invoice));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
