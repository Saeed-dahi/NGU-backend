<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\PreviewInvoiceItemRequest;
use App\Http\Resources\Invoice\PreviewInvoiceItemResource;
use App\Http\Traits\ApiResponser;
use App\Services\InvoiceItemsService;
use Illuminate\Http\Request;

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
        info($previewInvoiceItemRequest);
        $data = $this->invoiceItemService->invoiceItemPreview($previewInvoiceItemRequest);

        return $this->success($data);
    }
}
