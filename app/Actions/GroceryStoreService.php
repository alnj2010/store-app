<?php
namespace App\Actions;

use App\Models\Purchase;
use Http;
use Throwable;

class GroceryStoreService
{
    public function handle($ingredient): int
    {
        try {
            $quantity_sold = Http::get('https://recruitment.alegra.com/api/farmers-market/buy', [
                'ingredient' => $ingredient,
            ])['quantitySold'];

            if ($quantity_sold > 0) {
                Purchase::create([
                    'name_ingredient' => $ingredient,
                    'quantity' => $quantity_sold
                ]);
            }

            return $quantity_sold;

        } catch (Throwable $e) {

            return 0;
        }

    }
}