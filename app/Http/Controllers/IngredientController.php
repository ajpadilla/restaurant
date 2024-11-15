<?php

namespace App\Http\Controllers;

use App\Services\WareHouseClientService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

        $page = $request->query('page', 1);

        $pageSize = $request->query('pageSize', 10);

        $allIngredients = $this->wareHouseClientService->getAllIngredients($page, $pageSize);

        $totalResults = $allIngredients->total_results ?? count($allIngredients);

        $ingredients = new LengthAwarePaginator($allIngredients->ingredients, $totalResults, $pageSize, $page);

        return view('layouts.pages.ingredients', compact('ingredients'));
    }
}
