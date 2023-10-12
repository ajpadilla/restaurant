<?php

namespace App\Http\Controllers;

use App\Services\WareHouseClientService;
use Illuminate\Http\Request;

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

    public function index(Request $request) {
        $purchases = $this->wareHouseClientService->getAllPurchases();
        return view('layouts.pages.purchases', compact('purchases'));
    }
}
