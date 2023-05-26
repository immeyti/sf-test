<?php

namespace App\Driver\Queue;

interface QueueDriverInterface
{
    public function enqueueAtTail($queueName, $data): bool;
    public function dequeueTheFirst($queue): ?string;

    //TODO:: we should add the other methods
}
