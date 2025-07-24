<?php

namespace App\Enum\VisaPayment;

enum VisaPaymentStatus: string
{
    case RECEIVED = 'received';
    case DEPOSITED = 'deposited';
    case BOUNCED = 'bounced';
    case CANCELED = 'canceled';
}
