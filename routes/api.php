<?php

use App\Models\PurchasesHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/purchase-history', function (Request $request) {
    $purchases_history = PurchasesHistory::simplePaginate(4);
    return $purchases_history;
});
