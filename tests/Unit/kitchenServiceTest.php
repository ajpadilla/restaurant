<?php

namespace Tests\Unit;

use App\Repositories\Ingredient\EloquentIngredientRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Plate\PlateRepository;
use App\Services\WareHouseClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class kitchenServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
       // dd(DB::connection()->getDatabaseName());
    }
}
