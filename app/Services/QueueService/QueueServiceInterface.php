<?php

namespace App\Services\QueueService;

interface QueueServiceInterface
{
    public function enqueue($data): bool;
    public function dequeue(): ?object;
    public function getFirst(): ?object;
}
