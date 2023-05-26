<?php

namespace App\Providers;

use App\Driver\Queue\QueueDriverInterface;
use App\Driver\Queue\RedisQueueDriver;
use App\Driver\TripEstimator\TripApiEstimatorDriver;
use App\Driver\TripEstimator\TripEstimatorDriverInterface;
use App\Services\EstimatorService\DeliveryEstimatorServiceInterface;
use App\Services\EstimatorService\DeliveryEstimatorServiceService;
use App\Services\QueueService\DelayReportQueueService;
use App\Services\QueueService\FifoQueueStrategy;
use Illuminate\Foundation\Application;
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

        $this->app->bind(QueueDriverInterface::class, RedisQueueDriver::class);

        $this->app->bind(DelayReportQueueService::class, function (Application $app) {
            return new DelayReportQueueService($app->make(FifoQueueStrategy::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
