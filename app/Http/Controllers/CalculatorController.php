<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculatorRequest;
use App\Services\CalculatorService;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class CalculatorController extends Controller
{
    public function __construct(
        private readonly CalculatorService $calculatorService,
        private readonly ExchangeRateService $exchangeRateService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Calculator/Calculator');
    }

    public function calculate(CalculatorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $formattedCurrency = strtoupper($data['currencyCode']);
        $exchangeRates = $this->exchangeRateService->getExchangeRates($formattedCurrency, Carbon::parse($data['date']));

        $result = $this->calculatorService->calculate($exchangeRates, $data['amount'], $formattedCurrency);

        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
