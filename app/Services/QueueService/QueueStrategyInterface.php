<?php

namespace App\Services\QueueService;

interface QueueStrategyInterface
{
    public function enqueue(string $queueName, string $data): bool;
    public function dequeue(string $queueName): ?string;
    public function getFirst(string $queueName): ?string;
}
