<?php

namespace App\Services;

use App\Order;

class OrderIngredientService
{
    public function __construct()
    {
    }

    public function createIngredientListForKitchen(Order $order): array
    {
        $ingredientsListFromOrder = collect($order->plate->ingredients()->get()->toArray());
        $ingredients = $ingredientsListFromOrder->map(function ($item) {
            return ['name' => $item['name'], 'quantity' => $item['quantity']];
        })->toArray();

        return ['order_id' => $order->id, 'plate_id' => $order->plate->id, 'plate_name' => $order->plate->name , 'ingredients' => $ingredients];
    }
}
