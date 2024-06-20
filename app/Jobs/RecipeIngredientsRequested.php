<?php

namespace App\Jobs;

use App\Actions\GroceryStoreService;
use App\Jobs\RecipeIngredientsPurchased;
use App\Models\Ingredient;

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
        $TIME_TO_WAIT_FOR_PRODUCTS = 3;

        $has_all_ingredients = true;
        $this->order['ingredients'] = array_map(function ($required) use ($groceryStoreService, &$has_all_ingredients) {
            $quantity_required = $required["quantity"];
            $name = $required["name"];
            $was_obtained = $required["was_obtained"];
            if ($was_obtained) {
                return [
                    'name' => $name,
                    'quantity' => $quantity_required,
                    'was_obtained' => true
                ];
            }

            $store = Ingredient::firstWhere('name_ingredient', $name);

            if ($store->quantity >= $quantity_required) {
                $store->quantity -= $quantity_required;
                $store->save();
                return [
                    'name' => $name,
                    'quantity' => $quantity_required,
                    'was_obtained' => true
                ];
            }

            $quantity_sold = $groceryStoreService->handle($name);
            $store->quantity += $quantity_sold;

            if ($store->quantity >= $quantity_required) {
                $store->quantity -= $quantity_required;
                $store->save();
                return [
                    'name' => $name,
                    'quantity' => $quantity_required,
                    'was_obtained' => true
                ];
            }

            $store->save();
            $has_all_ingredients = false;
            return [
                'name' => $name,
                'quantity' => $quantity_required,
                'was_obtained' => false
            ];

        }, $this->order['ingredients']);


        if ($has_all_ingredients) {
            RecipeIngredientsPurchased::dispatch(["id" => $this->order["id"]]);
        } else {
            sleep($TIME_TO_WAIT_FOR_PRODUCTS);
            RecipeIngredientsRequested::dispatch($this->order);

        }

    }
}
