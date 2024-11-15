<?php

namespace App\Repositories\Order;

use App\Order;
use App\Plate;
use Illuminate\Support\Collection;

class EloquentOrderRepository implements OrderRepository
{
    /**
     * @var Order
     */
    private $order;

    /**
     * IngredientRepository constructor.
     * @param Order $model
     */
    function __construct(Order $model)
    {
        $this->order = $model;
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
     * @return Order
     */
    function create(array $input): Order
    {
        return $this->order->create($input);
    }

    /**
     * @param $id
     * @return Order|null
     */
    public function getById($id): ?Order
    {
        return $this->order->findOrFail($id);
    }

    /**
     * @param Order $order
     * @param array $attributes
     * @return bool
     */
    public function update(Order $order, array $attributes): bool
    {
        return $order->update($attributes);
    }

    /**
     * @param Order $order
     * @return bool|null
     */
    public function delete(Order $order): ?bool
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
        $query = $this->order
            ->distinct()
            ->select('orders.*');

        $joins = collect();


        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if (isset($filters['status'])) {
            $query->ofStatus($filters['status']);
        }

        if ($count) {
            return $query->count('orders.id');
        }

        return $query->orderBy('orders.id');
    }

    public function associatePlate(Order $order, Plate $plate): bool
    {
        $order->plate()->associate($plate);
        return $order->save();
    }
}
