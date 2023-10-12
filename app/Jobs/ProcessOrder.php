<?php

namespace App\Jobs;

use App\Order;
use App\Services\kitchenService;
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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->kitchenService = app(kitchenService::class);
            $this->kitchenService->prepareDish($this->order);
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
