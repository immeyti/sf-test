<?php

namespace App\Listeners;

use App\Events\OrderDelayed;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDelayReport
{
    private OrderService $orderService;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    /**
     * Handle the event.
     */
    public function handle(OrderDelayed $event): void
    {
        $this->orderService->addDelay($event->order, $event->newEstimate);
    }
}
