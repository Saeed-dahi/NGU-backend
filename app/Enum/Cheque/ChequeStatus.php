<?php

enum ChequeStatus: string
{
    case RECEIVED = 'received';
    case DEPOSITED = 'deposited';
    case BOUNCED = 'bounced';
    case CANCELED = 'canceled';
}
