<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceCommission;
use App\Services\Invoice\InvoiceCommissionServices;
use App\Services\Invoice\InvoiceItemsService;
use App\Services\Invoice\InvoiceService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ApiResponser, SharedFunctions;


    protected $invoiceItemsService, $invoiceService, $transactionService, $invoiceCommissionServices;

    public function __construct(
        InvoiceItemsService $invoiceItemsService,
        InvoiceService $invoiceService,
        TransactionService $transactionService,
        InvoiceCommissionServices $invoiceCommissionServices
    ) {
        $this->invoiceItemsService = $invoiceItemsService;
        $this->invoiceService = $invoiceService;
        $this->transactionService = $transactionService;
        $this->invoiceCommissionServices = $invoiceCommissionServices;
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
                ['invoice_number', 'type', 'account', 'goods_account', 'tax_account', 'tax_amount', 'discount_account']
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

        $this->updateInvoiceTransactions($invoice);

        return $this->success(InvoiceResource::make($invoice));
    }

    public function getInvoiceCost(Invoice $invoice)
    {
        $invoice->loadMissing('items.productUnit.product');
        $invoiceCost = $this->invoiceService->getInvoiceCost($invoice);

        return $this->success($invoiceCost);
    }


    public function getInvoiceCommission($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceCost = $this->invoiceService->getInvoiceCost($invoice);
        $invoiceCommission = $this->invoiceCommissionServices->getCommissionData($invoice, $invoiceCost['profit_total']);
        $newCommissionAmount = $this->invoiceCommissionServices->getInvoiceCommissionAmount($invoice, $invoiceCost['profit_total']);

        if ($invoiceCommission['commission_amount'] != $newCommissionAmount) {
            $this->invoiceCommissionServices->setInvoiceCommissionAmount($invoice, $invoiceCost['profit_total']);
            $invoiceCommission = $this->invoiceCommissionServices->getCommissionData($invoice, $invoiceCost['profit_total']);
            $this->updateInvoiceTransactions($invoice);
        }

        return $this->success($invoiceCommission);
    }

    public function createInvoiceCommission($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceCost = $this->invoiceService->getInvoiceCost($invoice);

        $invoice = $this->invoiceCommissionServices->createInvoiceCommission($invoice);
        $this->invoiceCommissionServices->setInvoiceCommissionAmount($invoice, $invoiceCost['profit_total']);
        $invoiceCommission = $this->invoiceCommissionServices->getCommissionData($invoice, $invoiceCost['profit_total']);

        return $this->success($invoiceCommission);
    }


    function updateInvoiceTransactions(Invoice $invoice)
    {
        // Delete Invoice transactions
        $this->transactionService->deleteTransactions($invoice);
        $this->invoiceService->createInvoiceTransaction($invoice);
        $invoice->load('transactions');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice) {}
}
