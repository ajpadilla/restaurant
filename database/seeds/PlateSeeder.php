<?php

use Illuminate\Database\Seeder;
use App\Repositories\PlateRepository;
use App\Repositories\IngredientRepository;
class PlateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        define("TOTAL_PLATES", 6);

        $plateRepository = app(PlateRepository::class);
        $ingredientRepository = app(IngredientRepository::class);

        for ($i = 0; $i <= TOTAL_PLATES; $i++) {
            $plateRepository->create(['name' => "Plate {$i}"]);
        }

        // Create Plates 1
        $ingredients = ['tomato','lemon','potato','rice','ketchup','lettuce','onion','cheese','meat','chicken'];
        $plate1 = $plateRepository->getById(1);

        foreach ($ingredients as $ingredient) {
           $ingredientModel = $ingredientRepository->getByName($ingredient);
           $plateRepository->attachIngredient($plate1, $ingredientModel);
        }

        // Create Plates 2
        $ingredients = ['tomato', 'ketchup', 'onion', 'cheese', 'lettuce', 'rice', 'meat', 'potato'];
        $plate2 = $plateRepository->getById(2);

        foreach ($ingredients as $ingredient) {
            $ingredientModel = $ingredientRepository->getByName($ingredient);
            $plateRepository->attachIngredient($plate2, $ingredientModel);
        }

        // Create Plates 4
        $ingredients = ['tomato', 'ketchup', 'onion', 'cheese', 'lettuce', 'rice', 'chicken', 'potato'];
        $plate4 = $plateRepository->getById(3);

        foreach ($ingredients as $ingredient) {
            $ingredientModel = $ingredientRepository->getByName($ingredient);
            $plateRepository->attachIngredient($plate4, $ingredientModel);
        }

        // Create Plates 5
        $ingredients = ['potato', 'ketchup', 'meat', 'tomato', 'cheese', 'onion', 'rice'];
        $plate5 = $plateRepository->getById(4);

        foreach ($ingredients as $ingredient) {
            $ingredientModel = $ingredientRepository->getByName($ingredient);
            $plateRepository->attachIngredient($plate5, $ingredientModel);
        }

        // Create Plates 5
        $ingredients = ['rice', 'meat', 'onion', 'tomato', 'potato'];
        $plate5 = $plateRepository->getById(5);

        foreach ($ingredients as $ingredient) {
            $ingredientModel = $ingredientRepository->getByName($ingredient);
            $plateRepository->attachIngredient($plate5, $ingredientModel);
        }

        // Create Plates 6
        $ingredients = ['potato', 'Cheese', 'ketchup', 'lemon'];
        $plate6 = $plateRepository->getById(6);

        foreach ($ingredients as $ingredient) {
            $ingredientModel = $ingredientRepository->getByName($ingredient);
            $plateRepository->attachIngredient($plate6, $ingredientModel);
        }
    }
}
