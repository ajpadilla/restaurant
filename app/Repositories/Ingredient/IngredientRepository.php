<?php

namespace App\Repositories\Ingredient;

use App\Ingredient;

interface IngredientRepository
{
    function create(array $input): Ingredient;

    public function getById($id): ?Ingredient;

    public function update(Ingredient $ingredient, array $attributes): bool;

    public function delete(Ingredient $ingredient): ?bool;

    public function search(array $filters = [], bool $count = false);
}
