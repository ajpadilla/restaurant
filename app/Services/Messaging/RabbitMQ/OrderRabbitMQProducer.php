<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class OrderRabbitMQProducer
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        // Conecta con RabbitMQ (ajusta las credenciales según tu configuración)
        $this->connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $this->channel = $this->connection->channel();
    }

    public function sendMessage($orderData = [], $queue = 'order-created')
    {
        // Reestablece la conexión y el canal antes de enviar el mensaje
        $this->connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $this->channel = $this->connection->channel();

        // Convertimos los datos a formato JSON
        $messageJson = json_encode($orderData);

        // Declara la cola y envía el mensaje
        $this->channel->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($messageJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->basic_publish($msg, '', $queue);

        \Log::info("Mensaje enviado a la cola {$queue}: " . $messageJson);

        // Cierra el canal y la conexión después de enviar el mensaje
        $this->channel->close();
        $this->connection->close();
    }

}
