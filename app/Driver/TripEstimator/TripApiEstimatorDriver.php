<?php

namespace App\Driver\TripEstimator;

use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Models\Trip;
use Illuminate\Support\Facades\Http;

class TripApiEstimatorDriver implements TripEstimatorDriverInterface
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('external_services.api_trip_estimator');
    }

    public function getEstimate(Trip $trip): int
    {
        // TODO:: uuid should belong to trip
        $response = Http::get(trim($this->baseUrl, '/') . '/122c2796-5df4-461c-ab75-87c1192b17f7');

        if ($response->failed()) {
            throw new FailedToGetDeliveryEstimate();
        }

        return $response->json('data.eta');
    }
}
