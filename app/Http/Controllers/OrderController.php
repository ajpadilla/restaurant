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

    /**
     * @param OrderRepository $orderRepository
     * @param IngredientRepository $ingredientRepository
     * @param WareHouseClientService $wareHouseClientService
     * @param PlateRepository $plateRepository
     * @param kitchenService $kitchenService
     */
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

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
