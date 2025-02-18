<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
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

    public function invoiceItemPreview(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'account_id' => 'nullable|exists:accounts,id',
            'product_unit_id' => 'nullable|integer|exists:product_units,id'
        ]);

        info($request);
        $query = $request->input('query');
        $accountId = $request->input('account_id');
        $productUnitId = $request->input('product_unit_id');

        $data = $this->invoiceItemService->invoiceItemPreview($query, $productUnitId, $accountId);

        return $this->success($data);
    }
}
