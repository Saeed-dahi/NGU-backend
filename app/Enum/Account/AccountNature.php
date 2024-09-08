<?php

namespace App\Enum\Account;

enum AccountNature: String
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
}
