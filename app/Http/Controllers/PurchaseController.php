<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'perPage' => 'integer|gt:0',
        ]);

        $per_page = $request->query('perPage') ?? 10;
        return Purchase::orderBy("id", "desc")->paginate($per_page);
    }
}
