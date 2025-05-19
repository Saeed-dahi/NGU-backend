<?php

namespace App\Enum\Invoice;

use Illuminate\Validation\Rules\Enum;

enum InvoiceType: String
{
    case SALES = 'sales';
    case PURCHASE = 'purchase';
    case PURCHASE_RETURN = 'purchase_return';
    case SALES_Return = 'sales_return';
}
