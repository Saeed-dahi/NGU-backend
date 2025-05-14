<?php

namespace App\Enum\Cheque;

enum ChequePaymentCases: string
{
    case MONTHLY = 'monthly';
    case EACH_WEEK = 'each_week';
    case EACH_FOUR_WEEKS = 'each_four_weeks';
    case SPECIFIC_DAYS = 'specific_days';
    case SPECIFIC_MONTHS = 'specific_months';
}
