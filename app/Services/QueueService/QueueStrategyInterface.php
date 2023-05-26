<?php

namespace App\Services\QueueService;

interface QueueStrategyInterface
{
    public function enqueue(string $queueName, string $data): bool;
    public function dequeue(string $queue): ?string;
}
