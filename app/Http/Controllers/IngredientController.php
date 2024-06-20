<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $TOTAL_OF_INGREDIENTS = 6;
        return Ingredient::simplePaginate($TOTAL_OF_INGREDIENTS);
    }
}
