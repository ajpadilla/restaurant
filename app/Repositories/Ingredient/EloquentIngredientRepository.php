<?php

namespace App\Repositories\Ingredient;

use App\Ingredient;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Collection;

class EloquentIngredientRepository implements IngredientRepository
{
    /**
     * @var Ingredient
     */
    private $ingredient;

    /**
     * IngredientRepository constructor.
     * @param Ingredient $model
     */
    function __construct(Ingredient $model)
    {
        $this->ingredient = $model;
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
     * @param array $input
     * @return Ingredient
     */
    function create(array $input): Ingredient
    {
        return $this->ingredient->create($input);
    }

    /**
     * @param $id
     * @return Order|null
     */
    public function getById($id): ?Ingredient
    {
        return $this->ingredient->findOrFail($id);
    }

    /**
     * @param Ingredient $ingredient
     * @param array $attributes
     * @return bool
     */
    public function update(Ingredient $ingredient, array $attributes): bool
    {
        return $ingredient->update($attributes);
    }

    /**
     * @param Ingredient $ingredient
     * @return bool|null
     */
    public function delete(Ingredient $ingredient): ?bool
    {
        return $ingredient->delete();
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->ingredient
            ->distinct()
            ->select('ingredients.*');

        $joins = collect();

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if (isset($filters['name'])) {
            $query->ofName($filters['name']);
        }

        if ($count) {
            return $query->count('ingredients.id');
        }

        return $query->orderBy('ingredients.id');
    }

    public function getByName($name)
    {
        return $this->search(['name' => $name])->first();
    }
}
