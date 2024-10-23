<?php

namespace App\Services;

use App\Enums\ConversionType;
use App\Enums\HnbApiVersion;
use App\Models\ExchangeRate;
use App\Services\ApiConfigurations\HnbApiConfiguration;
use App\Services\ApiDataTransformer\HnbApiTransformer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    const CUTOFF_DATE = '01-01-2023';

    public function __construct(
        private readonly HnbApiConfiguration $apiConfiguration,
        private readonly HnbApiTransformer   $transformer,
    )
    {
    }

    /**
     * @return Collection<ExchangeRate>
     */
    public function getExchangeRates(string $currencyCode, Carbon $chosenDate): Collection
    {
        // @todo: make Enum for supported currencies so the app doesn't error out if someone doesn't send a valid currency ERROR HANDLING!!!
        $formattedCurrency = strtoupper($currencyCode);
        $apiVersion = $this->getConversionType($chosenDate);

        if ($apiVersion->isToKnToEuro()) {
            return $this->getExchangeRatesV2($chosenDate, $formattedCurrency);
        }

        return $this->getExchangeRatesV3($chosenDate, $formattedCurrency);
    }

    public function getConversionType(Carbon $chosenDate): ConversionType
    {
        if ($chosenDate->lessThan(Carbon::parse(self::CUTOFF_DATE))) {
            return ConversionType::CURRENCY_TO_KN_TO_EURO;
        }

        return ConversionType::CURRENCY_TO_EURO;
    }

    /**
     * @return Collection<ExchangeRate>
     */
    private function fetchExchangeRates(Carbon $chosenDate, Collection $currencies): Collection
    {
        $apiVersion = $this->getApiVersion($chosenDate);
        $apiUrl = $this->apiConfiguration->getApiUrl($apiVersion);
        $query = $this->apiConfiguration->getQueryConfig(
            $chosenDate->format('Y-m-d'),
            $currencies->toArray(),
        );
        $response = Http::get("$apiUrl?$query");

        $exchangeRates = collect();

        foreach ($response->json() as $data) {
            $exchangeRates->push($this->transformer->transform($data, $apiVersion));
            $exchangeRates->each(fn(ExchangeRate $rate) => $rate->save());
        }

        return $exchangeRates;
    }

    private function getExchangeRatesV2(Carbon $date, $currency): Collection
    {
        $euro = 'EUR';
        $missingCurrencies = collect();

        $exchangeRates = ExchangeRate::whereIn('currency_iso_code', [$euro, $currency], 'or')
            ->where('application_date', $date->format('Y-m-d'))
            ->get();
        $fetchedCurrencies = $exchangeRates->map(fn(ExchangeRate $rate) => $rate->currency_iso_code);

        foreach ([$currency, $euro] as $neededCurrency) {
            if (!$fetchedCurrencies->contains($neededCurrency)) {
                $missingCurrencies->push($neededCurrency);
            }
        }

        if ($missingCurrencies->isNotEmpty()) {
            $exchangeRates = $exchangeRates->merge($this->fetchExchangeRates($date, $missingCurrencies));
        }

        return $exchangeRates;
    }

    private function getExchangeRatesV3(Carbon $date, string $currency): Collection
    {
        $exchangeRates = ExchangeRate::where('currency_iso_code', $currency)
            ->where('application_date', $date->format('Y-m-d'))
            ->get();

        if ($exchangeRates->isEmpty()) {
            $exchangeRates = $this->fetchExchangeRates($date, collect($currency));
        }

        return $exchangeRates;
    }

    private function getApiVersion(Carbon $chosenDate): HnbApiVersion
    {
        $conversionType = $this->getConversionType($chosenDate);

        if ($conversionType->isToKnToEuro()) {
            return HnbApiVersion::V2;
        }

        return HnbApiVersion::V3;
    }
}
