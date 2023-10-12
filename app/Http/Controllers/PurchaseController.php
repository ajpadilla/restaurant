<?php

namespace App\Http\Controllers;

use App\Services\WareHouseClientService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
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
    public function index(Request $request) {
        $purchases = $this->wareHouseClientService->getAllPurchases();
        return view('layouts.pages.purchases', compact('purchases'));
    }
}
