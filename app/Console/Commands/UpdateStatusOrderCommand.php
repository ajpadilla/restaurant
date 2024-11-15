<?php

namespace App\Console\Commands;

use App\Jobs\UpdateOrderStatusJob;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class UpdateStatusOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume {queue=update-status-order-command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Configura la conexión con RabbitMQ
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();

        // Define las colas y los respectivos jobs
        $queues = [
            'update-order-status-queue' => UpdateOrderStatusJob::class,
        ];

        // Itera sobre cada cola y configura su consumo
        foreach ($queues as $queueName => $jobClass) {
            // Declara la cola
            $channel->queue_declare($queueName, false, true, false, false);

            // Define el callback para el procesamiento del mensaje
            $callback = function ($msg) use ($jobClass) {
                try {
                    $this->consumeMsg($msg->body);
                    $jobClass::dispatch($msg->body);
                } catch (\Exception $e) {
                    \Log::error("Error procesando mensaje de {$msg->body}: " . $e->getMessage());
                }
            };

            // Configura el consumo de mensajes de la cola
            $channel->basic_consume($queueName, '', false, true, false, false, $callback);
        }

        // Mantiene la escucha activa hasta que se cierre el canal
        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function consumeMsg($msg)
    {
        // Decodifica el mensaje JSON
        $data = json_decode($msg, true); // El segundo parámetro 'true' convierte el JSON en un array asociativo

        // Verifica si la decodificación fue exitosa
        if ($data === null) {
            \Log::error('Error al decodificar el mensaje JSON: ' . json_last_error_msg());
            return;
        }

        // Log de los datos procesados
        \Log::info('Actualizando Order: ', $data);

        // Aquí puedes añadir lógica de procesamiento adicional, por ejemplo:
        // Procesar la orden y los ingredientes recibidos.
    }
}
