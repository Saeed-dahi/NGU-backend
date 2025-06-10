<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\PreviewInvoiceItemRequest;
use App\Http\Traits\ApiResponser;
use App\Services\Invoice\InvoiceItemsService;

class InvoiceItemsController extends Controller
{
    use ApiResponser;
    private $invoiceItemService;

    public function __construct(InvoiceItemsService $invoiceItemService)
    {
        $this->invoiceItemService = $invoiceItemService;
    }

    public function invoiceItemPreview(PreviewInvoiceItemRequest $previewInvoiceItemRequest)
    {
        $data = $this->invoiceItemService->invoiceItemPreview($previewInvoiceItemRequest);

        return $this->success($data);
    }
}
