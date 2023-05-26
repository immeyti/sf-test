<?php

namespace App\Driver\TripEstimator;

use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Models\Trip;

interface TripEstimatorDriverInterface
{
    /**
     * @throws FailedToGetDeliveryEstimate
     */
    public function getEstimate(Trip $trip): int;
}
