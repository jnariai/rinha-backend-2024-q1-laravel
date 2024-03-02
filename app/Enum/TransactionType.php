<?php

namespace App\Enum;

enum TransactionType: string
{
    case CREDIT = "c";
    case DEBIT = "d";
}
