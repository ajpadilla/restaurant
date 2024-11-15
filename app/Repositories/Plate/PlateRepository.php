<?php

namespace App\Repositories\Plate;

use App\Ingredient;
use App\Plate;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Collection;

interface PlateRepository
{
    function create(array $input): Plate;

    public function getById($id): ?Plate;

    public function update(Plate $order, array $attributes): bool;

    public function delete(Plate $order): ?bool;

    public function search(array $filters = [], bool $count = false);
}
