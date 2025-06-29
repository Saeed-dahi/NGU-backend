<?php

namespace App\Services\Invoice;

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\DiscountType;
use App\Enum\Invoice\InvoiceType;
use App\Http\Traits\SharedFunctions;
use App\Models\Invoice\Invoice;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceService
{
    use SharedFunctions;
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    function createInvoiceTransaction($invoice)
    {
        switch ($invoice->type) {
            case InvoiceType::SALES->value:
                $salesTransactions = $this->prepareSalesOrPurchaseReturnInvoiceTransactions($invoice, $invoice->type);
                $this->transactionService->createTransactions($invoice, $salesTransactions);
                break;
            case InvoiceType::PURCHASE->value:
                $purchaseTransactions = $this->preparePurchaseOrSalesReturnInvoiceTransactions($invoice, $invoice->type);
                $this->transactionService->createTransactions($invoice, $purchaseTransactions);
                break;
            case InvoiceType::SALES_Return->value:
                $purchaseTransactions = $this->preparePurchaseOrSalesReturnInvoiceTransactions($invoice, $invoice->type);
                $this->transactionService->createTransactions($invoice, $purchaseTransactions);
                break;
            case InvoiceType::PURCHASE_RETURN->value:
                $purchaseTransactions = $this->prepareSalesOrPurchaseReturnInvoiceTransactions($invoice, $invoice->type);
                $this->transactionService->createTransactions($invoice, $purchaseTransactions);
                break;
        }
    }

    function prepareSalesOrPurchaseReturnInvoiceTransactions($invoice, $invoiceType)
    {
        $transactions = [];

        // Customer Account
        $transactions[] = [
            'account_id' => $invoice->account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $invoice->total,
            'description' => request()->description ?? $invoiceType,
            'document_number' => $invoice->invoice_number,
        ];

        // Goods Account
        $transactions[] = [
            'account_id' => $invoice->goods_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $invoice->sub_total,
            'description' => request()->description ?? $invoiceType,
            'document_number' => $invoice->invoice_number,
        ];

        // Tax Account
        if ($invoice->tax_account_id && $invoice->tax_amount > 0) {
            $transactions[] = [
                'account_id' => $invoice->tax_account_id,
                'type' => AccountNature::CREDIT,
                'amount' => $invoice->tax_amount,
                'description' => request()->description ?? $invoiceType,
                'document_number' => $invoice->invoice_number,
            ];
        }

        // Discount Account
        if ($invoice->discount_account_id && $invoice->discount_amount > 0) {
            $transactions[] = [
                'account_id' => $invoice->discount_account_id,
                'type' => AccountNature::DEBIT,
                'amount' => $invoice->sub_total * ($invoice->discount_amount / 100), // Assuming discount is a percentage
                'description' => request()->description ?? $invoiceType,
                'document_number' => $invoice->invoice_number,
            ];
        }

        return $transactions;
    }

    function preparePurchaseOrSalesReturnInvoiceTransactions($invoice, $invoiceType)
    {
        $transactions = [];

        // Supplier Account
        $transactions[] = [
            'account_id' => $invoice->account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $invoice->total,
            'description' => request()->description ?? $invoiceType,
            'document_number' => $invoice->invoice_number,
        ];

        // Goods Account
        $transactions[] = [
            'account_id' => $invoice->goods_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $invoice->sub_total,
            'description' => request()->description  ?? $invoiceType,
            'document_number' => $invoice->invoice_number,
        ];

        // Tax Account
        if ($invoice->tax_account_id && $invoice->tax_amount > 0) {
            $transactions[] = [
                'account_id' => $invoice->tax_account_id,
                'type' => AccountNature::DEBIT,
                'amount' => $invoice->tax_amount,
                'description' => request()->description ?? $invoiceType,
                'document_number' => $invoice->invoice_number,
            ];
        }

        // Discount Account
        if ($invoice->discount_account_id && $invoice->discount_amount > 0) {
            $transactions[] = [
                'account_id' => $invoice->discount_account_id,
                'type' => AccountNature::CREDIT,
                'amount' => $invoice->sub_total * ($invoice->discount_amount / 100), // Assuming discount is a percentage
                'description' => request()->description ?? $invoiceType,
                'document_number' => $invoice->invoice_number,
            ];
        }

        return $transactions;
    }

    public function customInvoiceNavigateRecord($invoices, Model $model, Request $request, $column = 'id')
    {
        $direction = $request->input('direction');

        switch ($direction) {
            case 'next':
                $record = $invoices->where($column, '>', $model->$column)->first() ?? $invoices->first();
                break;
            case 'previous':
                $record = $invoices->where($column, '<', $model->$column)->latest($column)->first() ?? $invoices->latest($column)->first();
                break;
            case 'first':
                $record = $invoices->first();
                break;
            case 'last':
                $record = $invoices->latest($column)->first();
                break;
        }


        return $record ?? $model;
    }

    function calculateInvoiceDiscount(Invoice $invoice)
    {
        $discountAmount = 0;

        switch ($invoice->discount_type) {

            case DiscountType::AMOUNT->value:
                $discountAmount = $invoice->discount_amount;
                break;
            case DiscountType::PERCENTAGE->value:
                $discountAmount = $this->getDiscountMultiplier($invoice->discount_amount);
                break;
        }
        return $discountAmount;
    }

    function calculateInvoiceSubTotalAfterDiscount(Invoice $invoice, $subTotal)
    {
        $discountAmount = $this->calculateInvoiceDiscount($invoice);

        switch ($invoice->discount_type) {

            case DiscountType::AMOUNT->value:
                $subTotal -= $discountAmount;
                break;
            case DiscountType::PERCENTAGE->value:
                $subTotal *= $discountAmount;
                break;
        }

        return $subTotal;
    }


    function getInvoiceCost($invoice)
    {
        $items = $invoice->items->map(function ($item) use ($invoice) {
            $lastPurchase = $item->productUnit->getLastPurchaseDetails($invoice->date);
            return [
                'product_name'        => $item->productUnit->product->ar_name . ' - ' . $item->productUnit->product->en_name,
                'unit_name'           => $item->productUnit->unit->ar_name . ' - ' . $item->productUnit->unit->en_name,
                'invoice_item_price'  => $item->price,
                'last_purchase_price' => $lastPurchase?->price,
                'last_purchase_date'  => $lastPurchase?->date,
                'difference'          => $item->price - ($lastPurchase?->price ?? 0),
            ];
        });


        return [
            'items' => $items->toArray(),
            'invoice_total' => $invoice->sub_total,
            'cost_total'    => $invoice->invoiceCost($items),
            'profit_total'  =>  $invoice->sub_total -  $invoice->invoiceCost($items)
        ];
    }
}
