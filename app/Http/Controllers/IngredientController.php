<?php

namespace App\Http\Controllers;

use App\Services\WareHouseClientService;
use Illuminate\Http\Request;

class IngredientController extends Controller
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
        $ingredients = $this->wareHouseClientService->getAllIngredients();
        return view('layouts.pages.ingredients', compact('ingredients'));
    }
}
