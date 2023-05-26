<?php

namespace App\Providers;

use App\Driver\TripEstimator\TripApiEstimatorDriver;
use App\Driver\TripEstimator\TripEstimatorDriverInterface;
use App\Services\EstimatorService\DeliveryEstimatorServiceInterface;
use App\Services\EstimatorService\DeliveryEstimatorServiceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TripEstimatorDriverInterface::class, TripApiEstimatorDriver::class);
        $this->app->bind(DeliveryEstimatorServiceInterface::class, DeliveryEstimatorServiceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
