<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;

class DelayController extends Controller
{
    private OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    /**
     * @param Order $order
     * @return OrderResource
     */
    public function delayReport(Order $order): OrderResource
    {
        $this->orderService->delay($order);

        return OrderResource::make($order);
    }
}
