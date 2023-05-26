<?php

namespace App\Listeners;

use App\Events\OrderDelayed;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDelayReport
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(OrderDelayed $event): void
    {
        $this->orderService->addDelayReport($event->order, $event->newEstimate);
    }
}
