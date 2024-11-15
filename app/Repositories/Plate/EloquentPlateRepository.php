<?php

namespace App\Repositories\Plate;

use App\Ingredient;
use App\Order;
use App\Plate;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Collection;

class EloquentPlateRepository implements PlateRepository
{
    /**
     * @var Plate
     */
    private $plate;

    /**
     * IngredientRepository constructor.
     * @param Plate $model
     */
    function __construct(Plate $model)
    {
        $this->plate = $model;
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
     * @return Plate
     */
    function create(array $input): Plate
    {
        return $this->plate->create($input);
    }

    /**
     * @param $id
     * @return Plate|null
     */
    public function getById($id): ?Plate
    {
        return $this->plate->findOrFail($id);
    }

    /**
     * @param Plate $order
     * @param array $attributes
     * @return bool
     */
    public function update(Plate $order, array $attributes): bool
    {
        return $this->plate->update($attributes);
    }

    /**
     * @param Plate $order
     * @return bool|null
     */
    public function delete(Plate $order): ?bool
    {
        return $order->delete();
    }


    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->plate
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
