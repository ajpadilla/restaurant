<?php

namespace App\Services;

use App\Order;
use App\Repositories\OrderRepository;
use GuzzleHttp\Exception\GuzzleException;

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
     * @param WareHouseClientService $clientService
     * @param OrderRepository $orderRepository
     */
    public function __construct(WareHouseClientService $clientService, OrderRepository $orderRepository)
    {
        $this->clientService = $clientService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Order $order
     * @return void
     * @throws GuzzleException
     */
    public function prepareDish(Order $order){
       $ingredientsList = $order->plate->ingredients()->get()->toArray();
       $missingIngredients = $this->checkIngredients($ingredientsList);

       if (count($missingIngredients) === 0) {
           $this->orderRepository->update($order, ['status' => 'PROCESSED']);
           $this->decreaseIngredientsInWareHouse($ingredientsList);
       } else {
           foreach ($missingIngredients as $missingIngredient) {
               while ($missingIngredient['ingredient_in_ware_house']->quantity < $missingIngredient['ingredient']['quantity']) {
                   $this->buyIngredients($missingIngredient['ingredient']['name']);
                   $missingIngredient['ingredient_in_ware_house'] = $this->clientService->getIngredient($missingIngredient['ingredient']['name']);
               }
           }
           $this->orderRepository->update($order, ['status' => 'PROCESSED']);
           $this->decreaseIngredientsInWareHouse($ingredientsList);
       }
    }

    /**
     * @param array $ingredientsList
     * @return void
     * @throws GuzzleException
     */
    private function decreaseIngredientsInWareHouse(array $ingredientsList) {
        $decreaseIngredientsList = [];
        foreach ($ingredientsList as $item) {
            $decreaseIngredientsList[] = ['name' => $item['name'] ,'quantity' => $item['quantity']];
        }
        $this->clientService->decreaseIngredients($decreaseIngredientsList);
    }

    /**
     * @param array $ingredients
     * @return array
     * @throws GuzzleException
     */
    private function checkIngredients(array $ingredients): array
    {
        $ingredientNames = array_column($ingredients, 'name');
        $ingredientData = $this->clientService->getIngredients($ingredientNames);
        $missingIngredients = [];

        foreach ($ingredients as $ingredient) {
            $ingredientName = $ingredient['name'];
            $ingredientInWareHouse = $this->searchIngredient($ingredientData, $ingredientName);
            if ($ingredientInWareHouse->quantity == 0 || $ingredientInWareHouse->quantity < $ingredient['quantity']) {
                $missingIngredients[] = [
                    'ingredient' => $ingredient,
                    'ingredient_in_ware_house' => $ingredientInWareHouse,
                ];
            }
        }

        return $missingIngredients;
    }


    public function searchIngredient($ingredientInWareHouseList, $name) {
        foreach ($ingredientInWareHouseList as $ingredient) {
            if ($ingredient->name == $name) {
                return $ingredient;
            }
        }
        return null;
    }


    /**
     * @param $ingredientName
     * @return void
     * @throws GuzzleException
     */
    private function buyIngredients($ingredientName) {
        $response = $this->clientService->buyIngredient($ingredientName);
        $quantity = $response->quantitySold;
        if($quantity > 0) {
            $this->clientService->increaseIngredients(['name' => $ingredientName ,'quantity' => $quantity]);
            $this->clientService->makePurchase([
                'description' => "Buy {$ingredientName}",
                'product_name' => $ingredientName,
                'quantity' => $quantity
            ]);
        }
    }
}
