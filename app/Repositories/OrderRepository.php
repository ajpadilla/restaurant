<?php

namespace App\Repositories;

use App\Order;
use App\Plate;
use Illuminate\Support\Collection;

class OrderRepository extends AbstractRepository
{
    /**
     * OrderRepository constructor.
     * @param Order $model
     */
    function __construct(Order $model)
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
