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

    private $order;
    public function __construct($order) // TODO typing
    {
        $this->onQueue('purchased_ingredients');
        $this->order = $order;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
