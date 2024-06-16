<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecipeIngredientsPurchased implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $recipeIngredients;
    public function __construct($recipeIngredients) // TODO typing
    {
        $this->onQueue('purchased_ingredients');
        $this->recipeIngredients = $recipeIngredients;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
