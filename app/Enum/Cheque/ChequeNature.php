<?php

namespace App\Enum\Cheque;

enum ChequeNature: String
{
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';
}
