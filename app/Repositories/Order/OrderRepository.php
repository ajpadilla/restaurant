<?php

namespace App\Repositories\Order;

use App\Order;
use App\Plate;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Collection;

interface OrderRepository
{
    function create(array $input): Order;

    public function getById($id): ?Order;

    public function update(Order $order, array $attributes): bool;

    public function delete(Order $order): ?bool;

    public function search(array $filters = [], bool $count = false);
}
