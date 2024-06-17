<?php

use App\Models\PurchasesHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/purchase-history', function (Request $request) {
    return PurchasesHistory::simplePaginate(6);
});
