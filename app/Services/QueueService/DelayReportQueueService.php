<?php

namespace App\Services\QueueService;

class DelayReportQueueService implements QueueServiceInterface
{
//    TODO:: this class should get DelayReportId and return DelayReport object
    public function __construct(
        private readonly QueueStrategyInterface $queueStrategy,
        private readonly string $queueName = 'delay-reports'
    ) {}
    public function enqueue($data): bool
    {
        return $this->queueStrategy->enqueue($this->queueName, json_encode($data));
    }

    public function dequeue(): ?object
    {
        return json_decode($this->queueStrategy->dequeue($this->queueName));
    }

    public function getFirst(): ?object
    {
        return json_decode($this->queueStrategy->getFirst($this->queueName));
    }
}
