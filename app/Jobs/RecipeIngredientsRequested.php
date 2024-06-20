<?php

namespace App\Jobs;

use App\Actions\GroceryStoreService;
use App\Models\Ingredient;
use App\Models\Purchase;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecipeIngredientsRequested implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;
    public function __construct($order)
    {
        $this->onQueue('requested_ingredients');
        $this->order = $order;
    }


    public function handle(GroceryStoreService $groceryStoreService): void
    {
        $TIME_TO_WAIT_FOR_PRODUCTS=1;
        $ordered_ingredients = $this->order['ingredients'];
        $results = [];

        $has_all_ingredients = true;
        foreach ($ordered_ingredients as $ordered_ingredient) {
            $ingredient = Ingredient::firstWhere('name_ingredient', $ordered_ingredient['name']);

            $ordered_ingredient['model'] = $ingredient;
            $ordered_ingredient['is_purchased'] = $ingredient->quantity < $ordered_ingredient['quantity'];


            if (!$ordered_ingredient['is_purchased']) {
                $ingredient->quantity -= $ordered_ingredient['quantity'];
            } else {
                $quantity_sold = $groceryStoreService->handle($ordered_ingredient['name']);
                if ($quantity_sold > 0) {
                    Purchase::create([
                        'name_ingredient' => $ordered_ingredient['name'],
                        'quantity' => $quantity_sold
                    ]);
                }
                $ingredient->quantity += $quantity_sold;

                if ($ingredient->quantity >= $ordered_ingredient['quantity']) {
                    $ingredient->quantity -= $ordered_ingredient['quantity'];
                } else {
                    $has_all_ingredients = false;
                }
            }


            array_push($results, $ordered_ingredient);
        }

        if ($has_all_ingredients) {
            foreach ($results as $result) {
                $result['model']->save();
            }
            RecipeIngredientsPurchased::dispatch(["id" => $this->order["id"]]);
        } else {
            foreach ($results as $result) {
                if ($result['is_purchased']) {
                    $result['model']->save();
                }
            }
            $this->release(now()->addSeconds($TIME_TO_WAIT_FOR_PRODUCTS));
        }

    }
}
