<?php

namespace App\Services\QueueService;

class DelayReportQueueService implements QueueServiceInterface
{
    public function __construct(
        private readonly QueueStrategyInterface $queueStrategy,
        private readonly string $queueName = 'delay-reports'
    ) {}
    public function enqueue($data): bool
    {
        return $this->queueStrategy->enqueue($this->queueName, json_encode($data));
    }

    public function dequeue($queue): ?object
    {
        return json_decode($this->queueStrategy->dequeue($queue));
    }
}
