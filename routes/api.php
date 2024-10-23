<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/calculate', [\App\Http\Controllers\CalculatorController::class, 'calculate'])->name('calculate');
