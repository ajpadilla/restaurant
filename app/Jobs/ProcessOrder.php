<?php

namespace App\Jobs;

use App\Order;
use App\Services\kitchenService;
use App\Services\Messaging\MessageProducerInterface;
use App\Services\Messaging\RabbitMQ\OrderRabbitMQProducer;
use App\Services\OrderIngredientService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var kitchenService
     */
    protected $kitchenService;


    /**
     * @var OrderIngredientService
     */
    protected $orderIngredientService;


    /**
     * @var MessageProducerInterface
     *
     */
    protected $messageProducer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->queue = 'process_order_restaurant';
        $this->messageProducer = app(OrderRabbitMQProducer::class);
        $this->orderIngredientService = app(OrderIngredientService::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $orderIngredientsData = $this->orderIngredientService->createIngredientListForKitchen($this->order);
            $this->messageProducer->sendMessage($orderIngredientsData);
        } catch (Exception $exception) {
            logger("[Order Exception] {$this->order->id}");
            logger($exception->getMessage());
            logger($exception->getTraceAsString());
        } catch (GuzzleException $e) {
            logger($e->getMessage());
            logger($e->getTraceAsString());
        }
    }
}
