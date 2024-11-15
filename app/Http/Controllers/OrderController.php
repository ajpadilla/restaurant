<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrder;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Plate\PlateRepository;
use App\Services\kitchenService;
use App\Services\WareHouseClientService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    use DispatchesJobs;
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

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
     * @param WareHouseClientService $wareHouseClientService
     * @param PlateRepository $plateRepository
     * @param kitchenService $kitchenService
     */
    public function __construct(
        OrderRepository        $orderRepository,
        WareHouseClientService $wareHouseClientService,
        PlateRepository        $plateRepository,
        kitchenService $kitchenService
    )
    {
        $this->orderRepository = $orderRepository;
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
        $orders = $this->orderRepository->search(['status' => 'CREATED'])->paginate(10);
        return view('layouts.pages.orders', compact('orders'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function process(Request $request)
    {
        $orders = $this->orderRepository->search(['status' => 'PROCESSED'])->paginate(10);
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
