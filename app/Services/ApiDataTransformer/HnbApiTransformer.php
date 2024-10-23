<?php

namespace App\Services\ApiDataTransformer;

use App\Enums\HnbApiVersion;
use App\Models\ExchangeRate;

class HnbApiTransformer
{
    public function transform(array $data, HnbApiVersion $apiVersion): ExchangeRate
    {
        if ($apiVersion->isV2()) {
            return $this->transformV2($data);
        }

        return $this->transformV3($data);
    }

    private function transformV3(array $data): ExchangeRate
    {
        return new ExchangeRate([
                'currency_iso_code' => $data['valuta'],
                'buying_rate' => str_replace(',', '.', $data['kupovni_tecaj']),
                'selling_rate' =>str_replace(',', '.', $data['prodajni_tecaj']),
                'average_rate' =>str_replace(',', '.', $data['srednji_tecaj']),
                'application_date' => $data['datum_primjene'],
            ]
        );
    }

    private function transformV2(array $data): ExchangeRate
    {
        return new ExchangeRate([
                'currency_iso_code' => $data['valuta'],
                'buying_rate' => str_replace(',', '.', $data['kupovni_tecaj']),
                'selling_rate' =>str_replace(',', '.', $data['prodajni_tecaj']),
                'average_rate' =>str_replace(',', '.', $data['srednji_tecaj']),
                'application_date' => $data['datum'],
            ]
        );
    }
}
