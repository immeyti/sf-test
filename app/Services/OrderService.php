<?php

namespace App\Services;

use App\enums\TripStatusEnum;
use App\Events\OrderDelayed;
use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Exceptions\OrderDeliveryTimeIsNotEnded;
use App\Models\Order;
use Carbon\Carbon;

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
        if (!$this->isValidToDelayRequest($order)) {
            throw new OrderDeliveryTimeIsNotEnded();
        }

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


    /**
     * @param Order $order
     * @return bool
     */
    public function isValidToDelayRequest(Order $order): bool
    {
        return $order->expected_delivery_time < now();
    }
}
