<?php

namespace App\Services\Messaging;

interface MessageProducerInterface
{
    public function sendMessage(array $messageData, string $queue): void;

}
