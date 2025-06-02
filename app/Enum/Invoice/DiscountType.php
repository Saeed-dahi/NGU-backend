<?php

namespace App\Enum\Invoice;

enum DiscountType: String
{
    case PERCENTAGE = 'percentage';
    case AMOUNT = 'amount';
    
}
