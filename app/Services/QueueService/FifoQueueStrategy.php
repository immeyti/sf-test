<?php

namespace App\Services\QueueService;

use App\Driver\Queue\QueueDriverInterface;

class FifoQueueStrategy implements QueueStrategyInterface
{
    public function __construct(
        private readonly QueueDriverInterface $queueDriver
    ) {}

    public function enqueue($queueName, $data): bool
    {
        return $this->queueDriver->enqueueAtTail($queueName, $data);
    }

    public function dequeue($queue): ?string
    {
        return $this->queueDriver->dequeueTheFirst($queue);
    }
}
