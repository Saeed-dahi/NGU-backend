<?php

namespace App\Enum\Invoice;

use Illuminate\Validation\Rules\Enum;

enum InvoiceType: String
{
    case SALES = 'sales';
    case PURCHASE = 'purchase';
}
