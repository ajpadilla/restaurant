<?php

namespace App\Services;

use App\Jobs\TestJob;
use App\Order;
use App\Repositories\Order\OrderRepository;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

class kitchenService
{

    /**
     * @var WareHouseClientService
     */
    private $clientService;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var OrderIngredientService
     */
    protected $orderService;

    /**
     * @param WareHouseClientService $clientService
     * @param OrderRepository $orderRepository
     * @param OrderIngredientService $orderService
     */
    public function __construct(WareHouseClientService $clientService, OrderRepository $orderRepository, OrderIngredientService $orderService)
    {
        $this->clientService = $clientService;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    /**
     * @param Order $order
     * @return void
     * @throws GuzzleException
     * @throws Exception
     */
    public function prepareDish(Order $order){
       $ingredientsListFromOrder = collect($order->plate->ingredients()->get()->toArray());
       $missingIngredients = $this->checkIngredients($ingredientsListFromOrder);

       if (count($missingIngredients) === 0) {
           $this->orderRepository->update($order, ['status' => 'PROCESSED']);
           $this->decreaseIngredientsInWareHouse($ingredientsListFromOrder);
       } else {
           TestJob::dispatch(['data' => $this->prepareIngredientsListToWarehouse($order, $ingredientsListFromOrder)]);
       }
    }

    /**
     * @param Collection $ingredientsListFromOrder
     * @return array
     * @throws Exception
     */
    private function checkIngredients(Collection $ingredientsListFromOrder): array
    {
        $ingredientDataFromWarehouse = $this->clientService->getIngredients($ingredientsListFromOrder->pluck('name')->toArray());
        $missingIngredients = [];

        foreach ($ingredientsListFromOrder as $ingredientInOrder) {
            $ingredientInWareHouse = $this->searchIngredient(collect($ingredientDataFromWarehouse), $ingredientInOrder['name']);
            if ($ingredientInWareHouse->quantity == 0 || $ingredientInWareHouse->quantity < $ingredientInOrder['quantity']) {
                $missingIngredients[] = [
                    'ingredient' => $ingredientInOrder,
                    'ingredient_in_ware_house' => $ingredientInWareHouse,
                ];
            }
        }

        return $missingIngredients;
    }


    /**
     * @param Collection $ingredientInWareHouseList
     * @param $name
     * @return object|mixed|null
     */
    public function searchIngredient(Collection $ingredientInWareHouseList, $name): ?object {
        return $ingredientInWareHouseList->first(function ($ingredient) use ($name) {
            return $ingredient->name == $name;
        });
    }


    /**
     * @param Collection $ingredientsList
     * @return void
     * @throws GuzzleException
     */
    private function decreaseIngredientsInWareHouse(Collection $ingredientsList) {
        $decreaseIngredientsList = $ingredientsList->map(function ($item) {
            return['name' => $item['name'], 'quantity' => $item['quantity']];
        });
        $this->clientService->decreaseIngredients($decreaseIngredientsList);
    }

    /**
     * @param Order $order
     * @param Collection $ingredientsListFromOrder
     * @return array
     */
    function prepareIngredientsListToWarehouse(Order $order, Collection $ingredientsListFromOrder): array
    {
        $ingredients = $ingredientsListFromOrder->map(function ($item) {
            return['name' => $item['name'], 'quantity' => $item['quantity']];
        })->toArray();
        return['order_id' => $order->id, 'ingredients' => $ingredients];
    }
}
