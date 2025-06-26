<?php

namespace App\Enum\Cheque;


enum ChequeDiscountType: string
{
    case RECEIVED = 'received';
    case ALLOWED = 'allowed';
}
