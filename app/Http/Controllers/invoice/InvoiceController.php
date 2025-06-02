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
    public function index(Request $request)
    {
        $invoices = Invoice::where('type', $request->type)->get();

        return $this->success($invoices->map(fn($invoice) =>
        InvoiceResource::make($invoice, [
            'id',
            'invoice_number',
            'type',
            'date',
            'due_date',
            'status',
            'invoice_nature',
            'currency',
            'sub_total',
            'total',
            'notes',
            'account',
        ])));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $lastInvoice = Invoice::where('type', $request->type)->latest('id')->first();

        if ($lastInvoice) {
            $lastInvoice->invoice_number++;
            return $this->success(InvoiceResource::make(
                $lastInvoice,
                ['invoice_number', 'type', 'account', 'goods_account', 'tax_account', 'total_tax', 'discount_account', 'total_discount']
            ));
        }
        return $this->success(new Invoice([
            'invoice_number' => 1,
            'type' => $request->type
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $invoiceRequest)
    {
        $validatedItems = $this->invoiceItemsService->validateInvoiceItemsRequest();

        $validatedData = $invoiceRequest->validated();

        $validatedData['total_tax'] = 5;

        $invoice = Invoice::create($validatedData);

        $this->invoiceItemsService->createInvoiceItems($invoice, $validatedItems['items']);
        $this->invoiceService->createInvoiceTransaction($invoice);

        return $this->success(InvoiceResource::make($invoice));
    }

    /**
     * Display the specified resource.
     */
    public function show($query, Request $request)
    {

        $request->validate(['type' => 'required']);

        $invoicesQuery = Invoice::where('type', $request->type);
        $invoices = $invoicesQuery->get();

        $invoice = $query == 1 ? $invoices->first() : $invoices->where($request->get_by, $query)->first();
        if (!$invoice) abort(404);
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
