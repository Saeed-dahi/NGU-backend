<?php

namespace App\Enum\Invoice;

enum InvoiceCommissionType: string
{
    case TOTAL = 'total';
    case PROFIT = 'profit';
}
