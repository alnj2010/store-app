<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::insert([
            ['name_ingredient' => 'tomato', 'quantity' => 5],
            ['name_ingredient' => 'lemon', 'quantity' => 5],
            ['name_ingredient' => 'potato', 'quantity' => 5],
            ['name_ingredient' => 'rice', 'quantity' => 5],
            ['name_ingredient' => 'ketchup', 'quantity' => 5],
            ['name_ingredient' => 'lettuce', 'quantity' => 5],
            ['name_ingredient' => 'onion', 'quantity' => 5],
            ['name_ingredient' => 'cheese', 'quantity' => 5],
            ['name_ingredient' => 'meat', 'quantity' => 5],
            ['name_ingredient' => 'chicken', 'quantity' => 5],
        ]);

    }
}
