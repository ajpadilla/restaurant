<?php

namespace App\Http\Controllers;

use App\Repositories\IngredientRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PlateRepository;
use App\Services\kitchenService;
use App\Services\WareHouseClientService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Jobs\ProcessOrder;
use Illuminate\Foundation\Bus\DispatchesJobs;

class OrderController extends Controller
{
    use DispatchesJobs;
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var IngredientRepository
     */
    protected $ingredientRepository;

    /**
     * @var WareHouseClientService
     */
    protected $wareHouseClientService;

    /**
     * @var PlateRepository
     */
    protected $plateRepository;

    /**
     * @var kitchenService
     */
    protected $kitchenService;

    public function __construct(
        OrderRepository        $orderRepository,
        IngredientRepository   $ingredientRepository,
        WareHouseClientService $wareHouseClientService,
        PlateRepository        $plateRepository,
        kitchenService $kitchenService
    )
    {
        $this->orderRepository = $orderRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->wareHouseClientService = $wareHouseClientService;
        $this->plateRepository = $plateRepository;
        $this->kitchenService = $kitchenService;
    }

    public function create(Request $request)
    {
       // $this->ingredientRepository->create(['name' => 'Tomato', 'quantity' => 10]);
        //return $this->ingredientRepository->search([])->paginate(5);
       // return "OrderController::create";

       // $response = $this->wareHouseClientService->getAllIngredients();

        //$response = $this->wareHouseClientService->increaseIngredients(['name' => 'chicken' ,'quantity' => 5]);

        //$response = $this->wareHouseClientService->decreaseIngredients(['name' => 'chicken' ,'quantity' => 5]);

        //$response = $this->wareHouseClientService->makePurchase(['description' => 'Compra', 'product_name' => 'chicken', 'quantity' => 8]);

        //$response = $this->wareHouseClientService->getAllPurchases();

        //$response = $this->wareHouseClientService->buyIngredient('onion');

        //$response = $this->wareHouseClientService->getIngredient('meat');

        //var_dump($response->quantitySold);

        //return view('layouts.pages.home');

        $order = $this->orderRepository->getById(1);

        $this->kitchenService->prepareDish($order);
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepository->search(['status' => 'CREATED'])->get();

        return view('layouts.pages.orders', compact('orders'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function process(Request $request)
    {
        $orders = $this->orderRepository->search(['status' => 'PROCESSED'])->get();

        return view('layouts.pages.orders', compact('orders'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $plateId= rand(1, 6);
            $plate = $this->plateRepository->getById($plateId);
            $order = $this->orderRepository->create([
                'quantity' => 1,
                'plate_id' => $plate->id,
                'status' => "CREATED"
            ]);
            //$this->kitchenService->prepareDish($order);
            $job = (new ProcessOrder($order));
            $this->dispatch($job);
            DB::commit();
            return redirect()->route('plates.index')->with('success', '¡Order success created!');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('plates.index')->withErrors(["order_error"=>"{$exception->getMessage()}"]);
        }
    }
}
