<?php

namespace App\Http\Controllers\AdjustmentNote;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdjustmentNote\AdjustmentNoteRequest;
use App\Http\Resources\AdjustmentNote\AdjustmentNoteResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\AdjustmentNote\AdjustmentNote;
use App\Services\AdjustmentNote\AdjustmentNoteItemsService;
use App\Services\AdjustmentNote\AdjustmentNoteService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class AdjustmentNoteController extends Controller
{
    use SharedFunctions, ApiResponser;

    protected $adjustmentNoteService,
        $adjustmentNoteItemsService,
        $transactionService;


    public function __construct(
        AdjustmentNoteService $adjustmentNoteService,
        AdjustmentNoteItemsService $adjustmentNoteItemsService,
        TransactionService $transactionService
    ) {
        $this->adjustmentNoteService = $adjustmentNoteService;
        $this->adjustmentNoteItemsService = $adjustmentNoteItemsService;
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $AdjustmentNotes = AdjustmentNote::where('type', $request->type)->get();

        return $this->success(AdjustmentNoteResource::collection($AdjustmentNotes));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $lastAdjustmentNote = AdjustmentNote::latest('id')->first();

        if ($lastAdjustmentNote) {
            $lastAdjustmentNote->invoice_number++;
            return $this->success(AdjustmentNoteResource::make($lastAdjustmentNote));
        }
        return $this->success(
            new AdjustmentNote(['number' => 1, 'type' => $request->type])
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdjustmentNoteRequest $adjustmentNoteRequest)
    {
        $validatedItems = $this->adjustmentNoteItemsService->validateAdjustmentNoteItemsRequest();

        $validatedData = $adjustmentNoteRequest->validated();

        $adjustmentNote = AdjustmentNote::create($validatedData);

        $adjustmentNote->tax_amount = $this->calculateTaxAmount($adjustmentNote->sub_total);
        $adjustmentNote->total = $this->calculateTotalWithTax($adjustmentNote->sub_total);
        $adjustmentNote->save();

        if (isset($validatedItems['items'])) {
            $this->adjustmentNoteItemsService->createAdjustmentNoteItems($adjustmentNote, $validatedItems['items']);
        }

        $this->adjustmentNoteService->createAdjustmentTransaction($adjustmentNote);

        return $this->success(AdjustmentNoteResource::make($adjustmentNote));
    }

    /**
     * Display the specified resource.
     */
    public function show($query, Request $request)
    {
        // $adjustmentNote = $id == 1 ? AdjustmentNote::firstOrFail() : AdjustmentNote::findOrFail($id);
        // $adjustmentNote = $this->navigateRecord($adjustmentNote, $request);

        // return $this->success(AdjustmentNoteResource::make($adjustmentNote));


        $request->validate(['type' => 'required']);

        $invoicesQuery = AdjustmentNote::where('type', $request->type);
        $invoices = $invoicesQuery->get();

        $invoice = $query == 1 ? $invoices->first() : $invoices->where($request->get_by, $query)->first();
        if (!$invoice) abort(404);
        // $invoice = $this->invoiceService->customInvoiceNavigateRecord($invoicesQuery, $invoice, $request);


        return $this->success(AdjustmentNoteResource::make($invoice));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AdjustmentNoteRequest $adjustmentNoteRequest, AdjustmentNote $adjustmentNote)
    {
        $validatedItems = $this->adjustmentNoteItemsService->validateAdjustmentNoteItemsRequest();

        $adjustmentNote->update($adjustmentNoteRequest->validated());

        $adjustmentNote->tax_amount = $this->calculateTaxAmount($adjustmentNote->sub_total);
        $adjustmentNote->total = $this->calculateTotalWithTax($adjustmentNote->sub_total);
        $adjustmentNote->save();

        if (isset($validatedItems['items'])) {
            $this->adjustmentNoteItemsService->deleteAdjustmentNoteItems($adjustmentNote);
            $this->adjustmentNoteItemsService->createAdjustmentNoteItems($adjustmentNote, $validatedItems['items']);
            $adjustmentNote->load('items');
        }

        $this->transactionService->deleteTransactions($adjustmentNote);
        $this->adjustmentNoteService->createAdjustmentTransaction($adjustmentNote);
        $adjustmentNote->load('transactions');

        return $this->success(AdjustmentNoteResource::make($adjustmentNote));
    }
}
