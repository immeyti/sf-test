<?php

namespace App\Driver\Queue;

use Illuminate\Support\Facades\Redis;

class RedisQueueDriver implements QueueDriverInterface
{
    public function enqueueAtTail($queueName, $data): bool
    {
        return Redis::rpush($queueName, $data);
    }

    public function dequeueTheFirst($queueName): ?string
    {
        return Redis::lpop($queueName);
    }
}
