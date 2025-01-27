<?php

namespace App\Services;

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\InvoiceType;

class InvoiceService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService = null)
    {
        $this->transactionService = $transactionService;
    }

    function createInvoiceTransaction($invoice)
    {
        switch ($invoice->type) {
            case InvoiceType::SALES->value:
                $salesTransactions = $this->prepareSalesInvoiceTransactions($invoice);
                $this->transactionService->createTransactions($invoice, $salesTransactions);
                break;
            case InvoiceType::PURCHASE->value:
                $purchaseTransactions = $this->preparePurchaseInvoiceTransactions($invoice);
                $this->transactionService->createTransactions($invoice, $purchaseTransactions);
                break;
        }
    }

    function prepareSalesInvoiceTransactions($invoice)
    {
        $transactions = [];

        // Customer Account
        $transactions[] = [
            'account_id' => $invoice->account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $invoice->total,
            'description' => 'Sales Revenue',
            'document_number' => $invoice->number,
        ];

        // Goods Account
        $transactions[] = [
            'account_id' => $invoice->goods_account_id,
            'type' => AccountNature::CREDIT,
            'amount' => $invoice->total,
            'description' => 'Sales Revenue',
            'document_number' => $invoice->number,
        ];

        // Add tax and discount transactions
        $taxAndDiscountTransactions = $this->prepareTaxAndDiscountTransactions($invoice);
        $transactions = array_merge($transactions, $taxAndDiscountTransactions);

        return $transactions;
    }

    function preparePurchaseInvoiceTransactions($invoice)
    {
        $transactions = [];

        // Supplier Account
        $transactions[] = [
            'account_id' => $invoice->account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $invoice->total,
            'description' => 'Purchases',
            'document_number' => $invoice->number,
        ];

        // Goods Account
        $transactions[] = [
            'account_id' => $invoice->goods_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $invoice->total,
            'description' => 'Sales Revenue',
            'document_number' => $invoice->number,
        ];

        // Add tax and discount transactions
        $taxAndDiscountTransactions = $this->prepareTaxAndDiscountTransactions($invoice);
        $transactions = array_merge($transactions, $taxAndDiscountTransactions);

        return $transactions;
    }

    function prepareTaxAndDiscountTransactions($invoice)
    {
        $transactions = [];

        // Tax Account
        if ($invoice->total_tax_account && $invoice->total_tax > 0) {
            $transactions[] = [
                'account_id' => $invoice->total_tax_account,
                'type' => AccountNature::DEBIT,
                'amount' => $invoice->sub_total * ($invoice->total_tax / 100), // Assuming tax is a percentage
                'description' => 'Sales Tax',
                'document_number' => $invoice->number,
            ];
        }

        // Discount Account
        if ($invoice->total_discount_account && $invoice->total_discount > 0) {
            $transactions[] = [
                'account_id' => $invoice->total_discount_account,
                'type' => AccountNature::CREDIT,
                'amount' => $invoice->sub_total * ($invoice->total_discount / 100), // Assuming discount is a percentage
                'description' => 'Sales Discount',
                'document_number' => $invoice->number,
            ];
        }

        return $transactions;
    }
}
