<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisaPayment\VisaPaymentRequest;
use App\Http\Resources\VisaPayment\VisaPaymentResource;
use App\Models\VisaPayment\VisaPayment;
use App\Services\TransactionService;
use App\Services\VisaPayment\VisaPaymentItemsService;
use App\Services\VisaPayment\VisaPaymentService;
use Illuminate\Http\Request;

class VisaPaymentController extends Controller
{

    protected $visaPaymentService, $visaPaymentItemsService, $transactionService;

    public function __construct(VisaPaymentService $visaPaymentService, TransactionService $transactionService, VisaPaymentItemsService $visaPaymentItemsService)
    {
        $this->visaPaymentService = $visaPaymentService;
        $this->transactionService = $transactionService;
        $this->visaPaymentItemsService = $visaPaymentItemsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visaPayment = VisaPayment::orderBy('date', 'DESC')->get();
        return $this->success(VisaPaymentResource::collection($visaPayment));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedItems = $this->visaPaymentItemsService->validateVisaPaymentItemsRequest();

        $visaPayment = VisaPayment::create($request->validated());

        $this->visaPaymentItemsService->createVisaPaymentItems($visaPayment, $validatedItems['items']);
        $this->visaPaymentService->createVisaPaymentTransactions($visaPayment);

        return $this->success(VisaPaymentResource::make($visaPayment));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $visaPayment = $id == 1 ? VisaPayment::firstOrFail() : VisaPayment::findOrFail($id);
        $visaPayment = $this->navigateRecord($visaPayment, $request);

        return $this->success(VisaPaymentResource::make($visaPayment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, VisaPaymentRequest $request)
    {
        $visaPayment = VisaPayment::findOrFail($id);

        $validatedItems = $this->visaPaymentItemsService->validateVisaPaymentItemsRequest();
        $visaPayment->update($request->validated());

        $this->visaPaymentItemsService->deleteVisaPaymentItems($visaPayment);
        $this->visaPaymentItemsService->createVisaPaymentItems($visaPayment, $validatedItems['items']);
        $visaPayment->load('items');

        $this->transactionService->deleteTransactions($visaPayment);
        $this->visaPaymentService->createVisaPaymentTransactions($visaPayment);
        $visaPayment->load('transactions');

        $this->visaPaymentItemsService->createVisaPaymentItems($visaPayment, $validatedItems['items']);

        return $this->success(VisaPaymentResource::make($visaPayment));
    }

    function depositVisaPayment($id)
    {
        $visaPayment = VisaPayment::findOrFail($id);
        $this->visaPaymentService->depositVisaPayment($visaPayment);

        return $this->success(VisaPaymentResource::make($visaPayment));
    }
}
