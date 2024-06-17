<?php

namespace App\Jobs;

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
    public function __construct($order) // TODO typing
    {
        $this->onQueue('requested_ingredients');
        $this->order = $order;
    }
    public function handle(): void
    {
        Log::debug('--------enter with', $this->order);
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
                $quantity_sold = Http::get('https://recruitment.alegra.com/api/farmers-market/buy', [
                    'ingredient' => $ordered_ingredient['name'],
                ])['quantitySold'];
                if ($quantity_sold > 0) {
                    Log::debug('Save history ->', [
                        'name_ingredient' => $ordered_ingredient['name'],
                        'quantity' => $quantity_sold
                    ]);

                    Purchase::create([
                        'name_ingredient' => $ordered_ingredient['name'],
                        'quantity' => $quantity_sold
                    ]);
                }
                Log::debug($quantity_sold . " quantity of " . $ordered_ingredient['name'] . " was purchased ");
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
                Log::debug($result['name'] . " require " . $result['quantity'] . " and there are " . $result['model']->quantity . ' remaining');
            }
            RecipeIngredientsPurchased::dispatch(["id" => $this->order["id"]]);
        } else {
            foreach ($results as $result) {
                if ($result['is_purchased']) {
                    $result['model']->save();
                }
            }
            Log::debug('--------release 1');
            $this->release(now()->addSeconds(1));
        }
        Log::debug('--------End--------------------');

    }
}
