<?php

namespace App\Enums;

enum ConversionType
{
    case CURRENCY_TO_EURO;
    case CURRENCY_TO_KN_TO_EURO;

    public function isToKnToEuro() : bool
    {
        return $this === self::CURRENCY_TO_KN_TO_EURO;
    }
}
