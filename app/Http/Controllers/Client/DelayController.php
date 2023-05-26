<?php

namespace App\Http\Controllers\Client;

use App\Exceptions\CustomException;
use App\Exceptions\OrderDeliveryTimeIsNotEnded;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;

class DelayController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}
    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDelayReport(Order $order): \Illuminate\Http\JsonResponse
    {
        try {
            if (request()->user()->cannot('delayReport', $order)) {
                return $this->returnError(403);
            }

            $this->orderService->delay($order);

            return $this->returnSuccess(data: OrderResource::make($order));
        } catch (CustomException $e) {
            return $this->returnError($e->getCode(), [
                'message' => $e->getMessage()
            ]);
        }

    }
}
