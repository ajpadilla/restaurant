<?php

namespace App\Providers;

use App\Repositories\Ingredient\EloquentIngredientRepository;
use App\Repositories\Ingredient\IngredientRepository;
use App\Repositories\Order\EloquentOrderRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Plate\EloquentPlateRepository;
use App\Repositories\Plate\PlateRepository;
use App\Services\Messaging\MessageProducerInterface;
use App\Services\Messaging\RabbitMQ\OrderRabbitMQProducer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MessageProducerInterface::class, OrderRabbitMQProducer::class);
        $this->app->bind(OrderRepository::class, EloquentOrderRepository::class);
        $this->app->bind(PlateRepository::class, EloquentPlateRepository::class);
        $this->app->bind(IngredientRepository::class, EloquentIngredientRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
