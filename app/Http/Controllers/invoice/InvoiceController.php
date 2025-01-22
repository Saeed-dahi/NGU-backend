<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Invoice\Invoice;
use App\Services\InvoiceItemsService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ApiResponser, SharedFunctions;


    protected $invoiceItemsService;

    public function __construct(InvoiceItemsService $invoiceItemsService)
    {
        $this->invoiceItemsService = $invoiceItemsService;
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

        $validatedData['total_tax'] = 0;
        $validatedData['total_discount'] = 0;
        $invoice = Invoice::create($validatedData);

        $this->invoiceItemsService->createInvoiceItems($invoice, $validatedItems['items']);

        return $this->success(InvoiceResource::make($invoice));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $invoice = $id == 1 ? Invoice::first() : Invoice::find($id);
        $invoice = $this->navigateRecord($invoice, $request);
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
    public function update(InvoiceRequest $invoiceRequest, Invoice $invoice)
    {
        $validatedData = $this->invoiceItemsService->validateInvoiceItemsRequest();

        $invoice->update($invoiceRequest->validated());

        $this->invoiceItemsService->deleteInvoiceItems($invoice);

        $this->invoiceItemsService->createInvoiceItems($invoice, $validatedData['items']);

        $invoice->load('items');

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
