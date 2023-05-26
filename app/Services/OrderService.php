<?php

namespace App\Services;

use App\enums\TripStatusEnum;
use App\Events\OrderDelayed;
use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Exceptions\OrderDeliveryTimeIsNotEnded;
use App\Models\Order;
use App\Services\EstimatorService\DeliveryEstimatorServiceInterface;
use Illuminate\Support\Facades\Redis;

class OrderService
{

    public function __construct(
      private readonly DeliveryEstimatorServiceInterface $deliveryEstimator
    ) {}

    /**
     * @param Order $order
     * @return Order
     * @throws OrderDeliveryTimeIsNotEnded
     */
    public function delayReport(Order $order)
    {
        if (!$this->isValidToDelayReportRequest($order)) {
            throw new OrderDeliveryTimeIsNotEnded();
        }

        try {
            if ($this->orderHasActiveTrip($order)){
                $newEstimate = $this->deliveryEstimator->getEstimate($order);

                $order->increment('delivery_time', $newEstimate);
            } else {
                //TODO::
                Redis::rpush('order-delay-report', json_encode($order)); //TODO:: creat a service to handle queue
                $newEstimate = 0;
            }

            OrderDelayed::dispatch($order, $newEstimate); // TODO::
            return $order;
        } catch (FailedToGetDeliveryEstimate $e) {
            // TODO:: handle ...
        }
    }

    /**
     * @param Order $order
     * @param int $delayTime
     * @return Order
     */
    public function addDelayReport(Order $order, int $delayTime): Order
    {
        $order->delayReports()->create([
            'time' => $delayTime
        ]);

        return $order;
    }


    /**
     * @param Order $order
     * @return bool
     */
    public function isValidToDelayReportRequest(Order $order): bool
    {
        return $order->expected_delivery_time < now();
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function orderHasActiveTrip(Order $order): bool
    {
        return in_array($order->trip?->status, TripStatusEnum::getValidStatusListToNewEstimate());
    }
}
