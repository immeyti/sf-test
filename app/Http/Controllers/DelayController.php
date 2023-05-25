<?php

namespace App\Http\Controllers;

use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Exceptions\OrderDeliveryTimeIsNotEnded;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;

class DelayController extends Controller
{
    private OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function delayReport(Order $order): \Illuminate\Http\JsonResponse
    {
        try {
            if (request()->user()->cannot('delayReport', $order)) {
                return $this->returnError(403);
            }

            $this->orderService->delay($order);

            return $this->returnSuccess(data: OrderResource::make($order));
        } catch (OrderDeliveryTimeIsNotEnded $e) {
            return $this->returnError(400);
        } catch (\Exception $e) {
            return $this->returnError(400, ['message' => $e->getMessage()]);
        }

    }
}
