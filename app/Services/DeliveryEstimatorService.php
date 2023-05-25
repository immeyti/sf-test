<?php

namespace App\Services;

use App\Exceptions\FailedToGetDeliveryEstimate;
use Illuminate\Support\Facades\Http;

class DeliveryEstimatorService
{
    private string $endpoint;

    public function __construct()
    {
        $this->endpoint = env('ESTIMATOR_URL');
    }

    /**
     * @return int
     * @throws FailedToGetDeliveryEstimate
     */
    public function getNewEstimate(): int
    {
        $response = Http::get($this->endpoint);

        if ($response->failed()) {
            throw new FailedToGetDeliveryEstimate();
        }

        return $response->json('data.eta');
    }
}
