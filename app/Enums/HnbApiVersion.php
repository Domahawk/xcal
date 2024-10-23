<?php

namespace App\Enums;

enum HnbApiVersion
{
    case V2;
    case V3;

    public function isV2(): bool
    {
        return $this === self::V2;
    }
}
