<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

readonly class CalculatorService
{
    public function __construct(
        private ExchangeRateService $exchangeRateService
    )
    {
    }

    /**
     * @param Collection<ExchangeRate> $exchangeRates
     */
    public function calculate(Collection $exchangeRates, float $amount, string $askedCurrency): array
    {
        /** @var ExchangeRate $exRate */
        $exRate = $exchangeRates->filter(fn(ExchangeRate $rate) => $rate->currency_iso_code === $askedCurrency)->first();
        $conversionType = $this->exchangeRateService->getConversionType(Carbon::parse($exRate->application_date));

        if ($conversionType->isToKnToEuro()) {
            /** @var ExchangeRate $toEurExRate */
            $toEurExRate = $exchangeRates->filter(fn(ExchangeRate $rate) => $rate->currency_iso_code === 'EUR')->first();

            return $this->calculateCurrencyToKnToEur($exRate, $toEurExRate, $amount);
        }

        $eurAmount = $amount / $exRate->average_rate;

        return [$this->formatResult($eurAmount, $exRate)];
    }

    private function calculateCurrencyToKnToEur(ExchangeRate $toKnExRate, ExchangeRate $toEurExRate, float $amount): array
    {
        $results = collect();
        $results->push(
            $this->formatResult(
                $this->convertCurrencyToKn($toKnExRate, $amount),
                $toKnExRate
            )
        );
        $results->push(
            $this->formatResult(
                $this->convertCurrencyToEur($toEurExRate, $results->first()['amount']),
                $toEurExRate
            )
        );

        return $results->toArray();
    }

    private function convertCurrencyToKn(ExchangeRate $toKnExRate, float $amount): float
    {
        return $amount * $toKnExRate->average_rate;
    }

    private function convertCurrencyToEur(ExchangeRate $toKnExRate, float $amount): float
    {
        return $amount / $toKnExRate->average_rate;
    }

    private function formatResult(float $amount, ExchangeRate $exRate): array
    {
        return [
            'amount' => $amount,
            'currency' => $exRate->currency_iso_code,
            'exchangeRate' => $exRate->average_rate,
        ];
    }
}
