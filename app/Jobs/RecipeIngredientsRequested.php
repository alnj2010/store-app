<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecipeIngredientsRequested implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $recipeIngredients;
    public function __construct($recipeIngredients) // TODO typing
    {
        $this->onQueue('requested_ingredients');
        $this->recipeIngredients = $recipeIngredients;
    }
    public function handle(): void
    {
        // after buying the ingredients code ...
        echo 'delivered recipe ingredients--> ' . $this->recipeIngredients['id_recipe'];
        Log::debug('$this->recipeIngredients', $this->recipeIngredients);
        RecipeIngredientsPurchased::dispatch($this->recipeIngredients);
    }
}
