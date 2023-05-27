<?php

namespace App\Driver\Queue;

interface QueueDriverInterface
{
    public function enqueueAtTail($queueName, $data): bool;
    public function dequeueTheFirst($queueName): ?string;

    public function getTheFirst($queueName);

    //TODO:: we should add the other methods
}
