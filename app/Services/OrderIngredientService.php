<?php

namespace App\Services;

use App\Order;

class OrderService
{

    public function __construct()
    {
    }


    public function createIngredientListForKitchen(Order  $order): array
    {
        $ingredientsListFromOrder = collect($order->plate->ingredients()->get()->toArray());
        $ingredients = $ingredientsListFromOrder->map(function ($item) {
            return['name' => $item['name'], 'quantity' => $item['quantity']];
        })->toArray();

        return['order_id' => $order->id, 'ingredients' => $ingredients];
    }
}
