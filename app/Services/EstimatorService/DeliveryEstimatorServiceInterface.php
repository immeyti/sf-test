<?php

namespace App\Services\EstimatorService;

use App\Models\Order;

interface DeliveryEstimatorServiceInterface
{
    public function getEstimate(Order $order): int;
}
