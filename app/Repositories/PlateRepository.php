<?php

namespace App\Repositories;

use App\Ingredient;
use App\Plate;
use Illuminate\Support\Collection;

class PlateRepository extends AbstractRepository
{
    /**
     * @param Plate $model
     */
    function __construct(Plate $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $joins
     * @param $table
     * @param $first
     * @param $second
     * @param string $join_type
     */
    private function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner')
    {
        if (!$joins->has($table)) {
            $joins->put($table, json_encode(compact('first', 'second', 'join_type')));
        }
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->model
            ->distinct()
            ->select('plates.*');

        $joins = collect();


        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if ($count) {
            return $query->count('plates.id');
        }

        return $query->orderBy('plates.id');
    }

    public function attachIngredient(Plate $plate, Ingredient $ingredient) {
        $plate->ingredients()->attach($ingredient->id);
    }
}
