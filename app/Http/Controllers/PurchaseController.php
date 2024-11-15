<?php

namespace App\Http\Controllers;

use App\Services\WareHouseClientService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * @var WareHouseClientService
     */
    protected $wareHouseClientService;

    public function __construct(WareHouseClientService $wareHouseClientService)
    {
        $this->wareHouseClientService = $wareHouseClientService;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws GuzzleException
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);

        $pageSize = $request->query('pageSize', 10);

        $allPurchases = $this->wareHouseClientService->getAllPurchases($page, $pageSize);

        $totalResults = $allPurchases->total_results ?? count($allPurchases);

        $purchases = new LengthAwarePaginator($allPurchases->purchases, $totalResults, $pageSize, $page);

        return view('layouts.pages.purchases', compact('purchases'));
    }
}
