<?php

use Illuminate\Database\Seeder;
use \App\Repositories\IngredientRepository;
class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredientRepository = app(IngredientRepository::class);
        $ingredientsList = ['tomato','lemon','potato','rice','ketchup','lettuce','onion','cheese','meat','chicken'];

        foreach ($ingredientsList as $ingredient) {
            $quantity = rand(1, 5);
            $ingredientRepository->create(['name' => $ingredient, 'quantity' => $quantity]);
        }
    }
}
