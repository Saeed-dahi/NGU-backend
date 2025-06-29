<?php

namespace App\Services\Invoice;

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\InvoiceCommissionType;
use App\Models\Invoice\Invoice;

use Illuminate\Validation\Rule;

class InvoiceCommissionServices
{
    function getCommissionData(Invoice $invoice, $invoiceProfit)
    {
        $data = [];
        if ($invoice->agent_id) {
            $this->setInvoiceCommissionAmount($invoice, $invoiceProfit);
            $data = [
                'agent_id' => $invoice->agent_id,
                'commission_account_id' => $invoice->commission_account_id,
                'commission_type' => $invoice->commission_type,
                'commission_rate' => $invoice->commission_rate,
                'commission_amount' => $invoice->commission_amount,
            ];
        }

        return $data;
    }

    function ValidateInvoiceCommission()
    {
        return request()->validate([
            'agent_id' => 'required|exists:accounts,id',
            'commission_account_id' => 'required|exists:accounts,id',
            'commission_type' => [Rule::enum(InvoiceCommissionType::class), 'required'],
            'commission_rate' => 'required|numeric|max:255',
        ]);
    }
    function CreateInvoiceCommission(Invoice $invoice)
    {
        $validatedData = $this->ValidateInvoiceCommission();
        $invoice->update($validatedData);

        return $invoice;
    }

    function setInvoiceCommissionAmount(Invoice $invoice, $invoiceProfit)
    {
        switch ($invoice->commission_type) {
            case InvoiceCommissionType::TOTAL->value:
                $invoice->commission_amount = ($invoice->sub_total * $invoice->commission_rate) / 100;
                break;
            case InvoiceCommissionType::PROFIT->value:
                // $invoiceCost ??= $this->invoiceServices->getInvoiceCost($invoiceCommission->invoice)['profit_total'];
                $invoice->commission_amount = ($invoiceProfit * $invoice->commission_rate) / 100;
                break;
        }
        $invoice->save();
    }

    function prepareInvoiceCommissionTransactions(Invoice $invoice)
    {
        $transactions = [];

        // Agent Account
        $transactions[] = [
            'account_id' => $invoice->agent_id,
            'type' => AccountNature::CREDIT,
            'amount' => $invoice->commission_amount,
            'description' => 'commission' . $invoice->invoice_number,
            'document_number' => $invoice->invoice_number,
        ];

        // Goods Account
        $transactions[] = [
            'account_id' => $invoice->commission_account_id,
            'type' => AccountNature::DEBIT,
            'amount' => $invoice->commission_amount,
            'description' => 'commission' . $invoice->invoice_number,
            'document_number' => $invoice->invoice_number,
        ];


        return $transactions;
    }
}
