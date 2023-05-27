<?php

namespace Tests;

use App\Models\User;
use App\Services\QueueService\DelayReportQueueService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    /**
     * @return mixed
     */
    protected function createClient(): mixed
    {
        return User::factory()
            ->client()
            ->create();
    }

    /**
     * @return mixed
     */
    protected function createAgent(): mixed
    {
        return User::factory()
            ->agent()
            ->create();
    }

    protected function mockDelayReportQueueEnqueueMethod()
    {
        return $this->mock(DelayReportQueueService::class, function (MockInterface $mock) {
           $mock->shouldReceive('enqueue')->once()->andReturn(true);
        });
    }

    protected function mockDelayReportQueueDequeueMethod($returnValue)
    {
        return $this->mock(DelayReportQueueService::class, function (MockInterface $mock) use ($returnValue) {
            $mock->shouldReceive('dequeue')->once()->andReturn($returnValue);
        });
    }

    protected function mockDelayReportQueueGetFirstMethod($returnValue)
    {
        return $this->mock(DelayReportQueueService::class, function (MockInterface $mock) use ($returnValue) {
            $mock->shouldReceive('getFirst')->once()->andReturn($returnValue);
        });
    }
}
