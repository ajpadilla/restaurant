<?php

namespace App\Http\Controllers;

use App\Repositories\PlateRepository;
use Illuminate\Http\Request;

class PlateController extends Controller
{
    /** @var PlateRepository */
    private $plateRepository;
    public function __construct(PlateRepository $plateRepository)
    {
        $this->plateRepository = $plateRepository;
    }

    public function index(Request $request)
    {
        $plates = $this->plateRepository->search([])->get();

        return view('layouts.pages.plates', compact('plates'));
    }
}
