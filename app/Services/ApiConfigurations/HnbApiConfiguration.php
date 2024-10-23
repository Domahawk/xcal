<?php

namespace App\Services\ApiConfigurations;

use App\Enums\HnbApiVersion;

class HnbApiConfiguration
{
    public function getApiUrl(HnbApiVersion $apiVersion): string
    {
        $baseUrl = config('services.hnb.baseUrl');

        if ($apiVersion->isV2()) {
            return $baseUrl . config('services.hnb.apiVersions.v2');
        }

        return $baseUrl . config('services.hnb.apiVersions.v3');
    }

    public function getQueryConfig(string $date, array $currencies): string
    {
        $currencyQuery = '';
        $dateQuery = "datum-primjene=$date";

        foreach ($currencies as $currency) {
            $currencyQuery .= "&valuta=$currency";
        }

        return "$dateQuery$currencyQuery";
    }
}
