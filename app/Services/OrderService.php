<?php

namespace App\Services;

use App\enums\TripStatusEnum;
use App\Events\OrderDelayed;
use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Models\Order;

class OrderService
{
    private DeliveryEstimatorService $deliveryEstimator;

    public function __construct()
    {
        $this->deliveryEstimator = new DeliveryEstimatorService();
    }

    /**
     * @param Order $order
     * @return int|void
     */
    public function delay(Order $order)
    {
        try {
            if (in_array($order->trip->status, TripStatusEnum::getValidStatusListToNewEstimate())){
                $newEstimate = $this->deliveryEstimator->getNewEstimate();

                $order->increment('delivery_time', $newEstimate);

                OrderDelayed::dispatch($order, $newEstimate);

                return $order;
            }
        } catch (FailedToGetDeliveryEstimate $e) {

        }
    }

    /**
     * @param Order $order
     * @param int $delayTime
     * @return void
     */
    public function addDelay(Order $order, int $delayTime): void
    {
        $order->addDelay($delayTime);
    }
}
