<?php

namespace App\Jobs;

use App\Repositories\Order\OrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed
     */
    private $message;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->queue = 'update_order_status_Job';
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->orderRepository = app(OrderRepository::class);

            $messageJson = json_decode($this->message);

            Log::info("Numero de orden:", ['order' => $messageJson]);
            Log::info("Numero de orden a int:", ['order' => intval($messageJson->order_id)]);

            // Buscar la orden
            $foundOrder = $this->orderRepository->getById(intval($messageJson->order_id));
            Log::info("Orden encontrada:", ['order' => $foundOrder]);

            // Actualizar el estado de la orden
            $updated = $this->orderRepository->update($foundOrder, ['status' => 'PROCESSED']);
            Log::info("Resultado de la actualización:", ['updated' => $updated]);

            echo "El estado de la orden se ha actualizado exitosamente. Mensaje: {$this->message}\n";
        } catch (\Exception $e) {
            // Capturar y mostrar el error en la consola
            echo "Error al actualizar el estado de la orden: " . $e->getMessage() . "\n";

            // Registrar el error en los logs de Laravel
            Log::error("Error al actualizar el estado de la orden:", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

}
