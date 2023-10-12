<?php

namespace App\Http\Controllers;

use App\Services\WareHouseClientService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    /**
     * @var WareHouseClientService
     */
    protected $wareHouseClientService;

    /**
     * @param WareHouseClientService $wareHouseClientService
     */
    public function __construct(WareHouseClientService $wareHouseClientService)
    {
        $this->wareHouseClientService = $wareHouseClientService;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(Request $request) {
        $ingredients = $this->wareHouseClientService->getAllIngredients();
        return view('layouts.pages.ingredients', compact('ingredients'));
    }
}
