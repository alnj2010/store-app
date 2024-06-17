<?php

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/purchases', function (Request $request) {
    return Purchase::simplePaginate(6);
});

Route::get('/ingredients', function (Request $request) {
    return Purchase::simplePaginate(6);
});
