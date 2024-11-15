<?php

namespace App\Services\Messaging\RabbitMQ;

use App\Services\Messaging\MessageProducerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class OrderRabbitMQProducer implements MessageProducerInterface
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
    }

    public function sendMessage(array $messageData, string $queue = 'order-created'): void
    {

        $this->connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $this->channel = $this->connection->channel();

        // Convertimos los datos a formato JSON
        $messageJson = json_encode($messageData);

        // Declara la cola y envía el mensaje
        $this->channel->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($messageJson, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->basic_publish($msg, '', $queue);

        \Log::info("Mensaje enviado a la Cocina {$queue}: " . $messageJson);

        // Cierra el canal y la conexión después de enviar el mensaje
       $this->channel->close();
       $this->connection->close();
    }
}
