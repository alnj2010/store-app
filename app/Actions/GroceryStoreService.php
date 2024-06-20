<?php
namespace App\Actions;

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

            return $quantity_sold;

        } catch (Throwable $e) {

            return 0;
        }

    }
}