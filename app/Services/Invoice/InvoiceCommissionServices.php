<?php

namespace App\Services\Invoice;

use App\Enum\Account\AccountNature;
use App\Enum\Invoice\InvoiceCommissionType;
use App\Http\Resources\Account\AccountResource;
use App\Models\Invoice\Invoice;

use Illuminate\Validation\Rule;

class InvoiceCommissionServices
{
    function getCommissionData(Invoice $invoice, $invoiceProfit)
    {
        $data = [];
        $customAccountFields = ['id', 'code', 'ar_name', 'en_name'];

        if ($invoice->agent_id) {

            $data = [
                'agent_account' => new AccountResource($invoice->agentAccount, $customAccountFields),
                'commission_account' => new AccountResource($invoice->commissionAccount, $customAccountFields),
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
        $invoice->commission_amount = $this->getInvoiceCommissionAmount($invoice, $invoiceProfit);
        $invoice->save();
    }

    function getInvoiceCommissionAmount(Invoice $invoice, $invoiceProfit)
    {
        switch ($invoice->commission_type) {
            case InvoiceCommissionType::TOTAL->value:
                return ($invoice->sub_total * $invoice->commission_rate) / 100;
                break;
            case InvoiceCommissionType::PROFIT->value:
                // $invoiceCost ??= $this->invoiceServices->getInvoiceCost($invoiceCommission->invoice)['profit_total'];
                return ($invoiceProfit * $invoice->commission_rate) / 100;
                break;
        }
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
