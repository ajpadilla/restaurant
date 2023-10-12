<?php

namespace App\Http\Controllers;

use App\Repositories\PlateRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlateController extends Controller
{
    /** @var PlateRepository */
    private $plateRepository;
    public function __construct(PlateRepository $plateRepository)
    {
        $this->plateRepository = $plateRepository;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $plates = $this->plateRepository->search([])->get();

        return view('layouts.pages.plates', compact('plates'));
    }
}
