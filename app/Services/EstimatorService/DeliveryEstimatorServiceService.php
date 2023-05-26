<?php

namespace App\Services\EstimatorService;

use App\Driver\TripEstimator\TripEstimatorDriverInterface;
use App\Exceptions\OrderDoesNotHaveAnyTrip;
use App\Models\Order;

class DeliveryEstimatorServiceService implements DeliveryEstimatorServiceInterface
{
    public function __construct(
        private readonly TripEstimatorDriverInterface $tripEstimatorDriver
    ) {}

    /**
     * @return int
     * @throws OrderDoesNotHaveAnyTrip|\App\Exceptions\FailedToGetDeliveryEstimate
     */
    public function getEstimate(Order $order): int
    {
        if (! $order->trip) {
            throw new OrderDoesNotHaveAnyTrip();
        }
        return $this->tripEstimatorDriver->getEstimate($order->trip);
    }
}
