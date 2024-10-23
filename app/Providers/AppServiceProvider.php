<?php

namespace App\Providers;

use App\Services\ApiConfigurations\HnbApiConfiguration;
use App\Services\ApiDataTransformer\HnbApiTransformer;
use App\Services\CalculatorService;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ExchangeRateService::class, function () {
            return new ExchangeRateService(
                $this->app->make(HnbApiConfiguration::class),
                $this->app->make(HnbApiTransformer::class),
            );
        });
        $this->app->singleton(CalculatorService::class, function () {
            return new CalculatorService($this->app->get(ExchangeRateService::class));
        });
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
