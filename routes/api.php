<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;


Route::apiResource('purchases', PurchaseController::class)->only(['index']);

Route::apiResource('ingredients', IngredientController::class)->only(['index']);
